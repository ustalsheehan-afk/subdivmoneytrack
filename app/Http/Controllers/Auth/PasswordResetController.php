<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetLinkMail;
use App\Models\Admin;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    private const TOKEN_EXPIRY_MINUTES = 60;

    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $this->normalizeEmail($request->input('email', '')),
        ]);

        $request->validate([
            'email' => ['required', 'string', 'email:rfc'],
        ]);

        $email = $this->normalizeEmail($request->string('email')->toString());
        $user = $this->resolveResettableUser($email);

        if ($user) {
            $plainToken = Str::random(80);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($plainToken),
                    'created_at' => now(),
                ]
            );

            try {
                Mail::to($user->email)->send(new PasswordResetLinkMail(
                    user: $user,
                    resetUrl: route('password.reset', [
                        'token' => $plainToken,
                        'email' => $email,
                    ], true),
                    expiresInMinutes: self::TOKEN_EXPIRY_MINUTES,
                ));

                logger()->info('Password reset link generated.', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            } catch (\Throwable $e) {
                logger()->error('Failed to send password reset email.', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);

                DB::table('password_resets')->where('email', $email)->delete();

                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'We could not send the password reset email right now. Please try again later.']);
            }
        } else {
            logger()->info('Password reset requested for unknown email.', [
                'email' => $email,
            ]);
        }

        return back()->with('status', 'If the email exists, a reset link has been sent.');
    }

    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', old('email')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => $this->normalizeEmail($request->input('email', '')),
        ]);

        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ]);

        $email = $this->normalizeEmail($request->string('email')->toString());
        $resetRow = DB::table('password_resets')->where('email', $email)->first();

        if (! $resetRow) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link is invalid or has already been used.']);
        }

        if (Carbon::parse($resetRow->created_at)->addMinutes(self::TOKEN_EXPIRY_MINUTES)->isPast()) {
            DB::table('password_resets')->where('email', $email)->delete();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link has expired. Please request a new one.']);
        }

        if (! Hash::check($request->input('token'), $resetRow->token)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link is invalid or has already been used.']);
        }

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            DB::table('password_resets')->where('email', $email)->delete();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link is invalid or has already been used.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_resets')->where('email', $email)->delete();

        logger()->info('Password reset completed.', [
            'user_id' => $user->id,
            'email' => $email,
        ]);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
    }

    private function resolveResettableUser(string $email): ?User
    {
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($user) {
            return $user;
        }

        $admin = Admin::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($admin) {
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $admin->name ?? 'Admin',
                    'password' => $admin->password,
                    'role' => 'admin',
                    'active' => true,
                ]
            );
        }

        $resident = Resident::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($resident) {
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => trim(($resident->first_name ?? '') . ' ' . ($resident->last_name ?? '')) ?: 'Resident',
                    'password' => $resident->getRawOriginal('password') ?: Hash::make(Str::random(32)),
                    'role' => 'resident',
                    'active' => true,
                ]
            );
        }

        return null;
    }

    private function normalizeEmail(string $email): string
    {
        return mb_strtolower(trim($email));
    }
}
