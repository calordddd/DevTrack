<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Mail\VerificationCodeMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_fails_without_verification_code(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_send_verification_code(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/register/send-code', [
            'email' => 'johndoe@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertNotNull(Cache::get('email_verification_code_johndoe@example.com'));

        Mail::assertSent(VerificationCodeMail::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com') && strlen($mail->code) === 6;
        });
    }

    public function test_verify_code_fails_with_invalid_code(): void
    {
        Cache::put('email_verification_code_johndoe@example.com', '123456', now()->addMinutes(15));

        $response = $this->postJson('/api/register/verify-code', [
            'email' => 'johndoe@example.com',
            'code' => '654321',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Invalid or expired verification code.'
        ]);
    }

    public function test_complete_registration_flow_success(): void
    {
        Mail::fake();

        // 1. Send verification code
        $sendResponse = $this->postJson('/api/register/send-code', [
            'email' => 'johndoe@example.com',
        ]);
        $sendResponse->assertStatus(200);
        
        $code = Cache::get('email_verification_code_johndoe@example.com');
        $this->assertNotNull($code);

        // 2. Verify code
        $verifyResponse = $this->postJson('/api/register/verify-code', [
            'email' => 'johndoe@example.com',
            'code' => $code,
        ]);
        $verifyResponse->assertStatus(200);
        $this->assertTrue(Cache::get('email_verified_for_registration_johndoe@example.com'));

        // 3. Register user
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $registerResponse->assertStatus(201);
        $registerResponse->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'name', 'email']
        ]);

        // Check cache cleaned up
        $this->assertNull(Cache::get('email_verification_code_johndoe@example.com'));
        $this->assertNull(Cache::get('email_verified_for_registration_johndoe@example.com'));

        // Welcome mail sent
        Mail::assertSent(WelcomeMail::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com') &&
                   $mail->user->name === 'John Doe';
        });
    }
}
