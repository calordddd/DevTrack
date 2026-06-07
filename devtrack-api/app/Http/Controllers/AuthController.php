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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
