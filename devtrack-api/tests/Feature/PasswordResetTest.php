<?php

namespace Tests\Feature;

use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_fails_if_email_does_not_exist(): void
    {
        $response = $this->postJson('/api/password/forgot', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_forgot_password_sends_verification_code(): void
    {
        Mail::fake();
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/api/password/forgot', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertNotNull(Cache::get('password_reset_code_user@example.com'));

        Mail::assertSent(PasswordResetCodeMail::class, function ($mail) {
            return $mail->hasTo('user@example.com') && strlen($mail->code) === 6;
        });
    }

    public function test_reset_password_fails_with_invalid_code(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);
        Cache::put('password_reset_code_user@example.com', '123456', now()->addMinutes(15));

        $response = $this->postJson('/api/password/reset', [
            'email' => 'user@example.com',
            'code' => '654321',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Invalid or expired reset code.'
        ]);
    }

    public function test_reset_password_fails_if_password_confirmation_does_not_match(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);
        Cache::put('password_reset_code_user@example.com', '123456', now()->addMinutes(15));

        $response = $this->postJson('/api/password/reset', [
            'email' => 'user@example.com',
            'code' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_reset_password_succeeds_with_correct_code(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);
        Cache::put('password_reset_code_user@example.com', '123456', now()->addMinutes(15));

        $response = $this->postJson('/api/password/reset', [
            'email' => 'user@example.com',
            'code' => '123456',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Password reset successfully.'
        ]);

        $this->assertNull(Cache::get('password_reset_code_user@example.com'));

        // Refresh user from DB and check password matches
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
