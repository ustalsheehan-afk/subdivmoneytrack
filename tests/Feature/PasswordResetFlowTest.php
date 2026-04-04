<?php

namespace Tests\Feature;

use App\Mail\PasswordResetLinkMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_generic_response_and_sends_reset_mail_for_known_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'resident@example.com',
            'role' => 'resident',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status', 'If the email exists, a reset link has been sent.');
        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        Mail::assertSent(PasswordResetLinkMail::class, function (PasswordResetLinkMail $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_it_resets_password_with_valid_token_and_deletes_it(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('OldPassword123'),
        ]);

        $plainToken = str_repeat('a', 80);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'email' => $user->email,
            'token' => $plainToken,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertRedirect(route('login'));

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword123', $user->password));
        $this->assertDatabaseMissing('password_resets', [
            'email' => $user->email,
        ]);
    }
}
