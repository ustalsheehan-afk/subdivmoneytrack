<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Resident;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = trim(strtolower($request->input('email')));
        $password = $request->input('password');

        // Standard auth attempt (users table)
        if (Auth::attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is not active.']);
            }

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('resident.home'));
        }

        // Fallback for legacy admins/residents tables (if used in this deployment)
        $user = $this->findAndSyncLegacyUser($email, $password);
        if ($user && Auth::loginUsingId($user->id, $request->filled('remember'))) {
            $request->session()->regenerate();
            if (!$user->active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is not active.']);
            }
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('resident.home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function findAndSyncLegacyUser(string $email, string $password)
    {
        // Try Admin legacy table first
        $legacy = Admin::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($legacy && Hash::check($password, $legacy->password)) {
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $legacy->name ?? 'Admin',
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'active' => true,
                ]
            );
        }

        // Try Resident legacy table
        $legacy = Resident::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($legacy && Hash::check($password, $legacy->password)) {
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => trim(($legacy->first_name ?? '') . ' ' . ($legacy->last_name ?? '')) ?: 'Resident',
                    'password' => Hash::make($password),
                    'role' => 'resident',
                    'active' => true,
                ]
            );
        }

        return null;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
