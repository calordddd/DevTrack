<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register a new user after verifying their email.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $email = $request->email;
        if (!Cache::get('email_verified_for_registration_' . $email)) {
            return response()->json([
                'message' => 'Email verification is required or has expired.',
                'errors' => [
                    'email' => ['Please verify your email address before registering.']
                ]
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        // Clear verification cache flags
        Cache::forget('email_verification_code_' . $email);
        Cache::forget('email_verified_for_registration_' . $email);

        $token = $user->createToken('auth_token')->plainTextToken;

        try {
            Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('Welcome email failed to send', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * Generate and send a 6-digit email verification code for registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(100000, 999999));

        Cache::put('email_verification_code_' . $email, $code, now()->addMinutes(15));
        Log::info("Verification code for {$email}: {$code}");

        try {
            Mail::to($email)->send(new VerificationCodeMail($code));
        } catch (\Exception $e) {
            Log::error('Failed to send verification code email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            // Return success with warning so user doesn't break locally
            return response()->json([
                'message' => 'Verification code generated (check logs if mail fails).',
                'mail_error' => $e->getMessage()
            ], 200);
        }

        return response()->json([
            'message' => 'Verification code sent successfully.'
        ]);
    }

    /**
     * Verify the registration email code and save confirmation to cache.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'code' => 'required|string|size:6',
        ]);

        $email = $request->email;
        $code = $request->code;

        $cachedCode = Cache::get('email_verification_code_' . $email);

        if (!$cachedCode || $cachedCode !== $code) {
            return response()->json([
                'message' => 'Invalid or expired verification code.'
            ], 422);
        }

        // Verification successful, cache the verification flag for 15 minutes.
        Cache::put('email_verified_for_registration_' . $email, true, now()->addMinutes(15));

        return response()->json([
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * Authenticate user and issue Sanctum token.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Terminate the current user token session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Retrieve the currently logged in user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Generate and send a password reset code to user's email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(100000, 999999));

        Cache::put('password_reset_code_' . $email, $code, now()->addMinutes(15));
        Log::info("Password reset code for {$email}: {$code}");

        try {
            Mail::to($email)->send(new \App\Mail\PasswordResetCodeMail($code));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset code email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Password reset code generated (check logs if mail fails).',
                'mail_error' => $e->getMessage()
            ], 200);
        }

        return response()->json([
            'message' => 'Password reset code sent successfully.'
        ]);
    }

    /**
     * Validate verification code and reset password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->email;
        $code = $request->code;

        $cachedCode = Cache::get('password_reset_code_' . $email);

        if (!$cachedCode || $cachedCode !== $code) {
            return response()->json([
                'message' => 'Invalid or expired reset code.'
            ], 422);
        }

        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Cache::forget('password_reset_code_' . $email);

        return response()->json([
            'message' => 'Password reset successfully.'
        ]);
    }
}
