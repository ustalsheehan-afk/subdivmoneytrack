<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Resident;

class ResidentLoginController extends Controller
{
    /**
     * Show the resident login form.
     */
    public function showLoginForm()
    {
        return view('auth.resident-login'); // Make sure you have this Blade view
    }

    /**
     * Handle a login request for resident.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to login using resident guard
        $email = trim(strtolower($credentials['email']));
        $password = $credentials['password'];

        if (Auth::guard('resident')->attempt(['email' => $email, 'password' => $password], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('resident.home'));
        }

        // Legacy fallback using residents table
        $legacy = Resident::whereRaw('LOWER(email) = ?', [$email])->first();
        if ($legacy && Hash::check($password, $legacy->password)) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => trim(($legacy->first_name ?? '') . ' ' . ($legacy->last_name ?? '')) ?: 'Resident',
                    'password' => Hash::make($password),
                    'role' => 'resident',
                    'active' => true,
                ]
            );
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('resident.home'));
        }

        // Failed login
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout resident.
     */
    public function logout(Request $request)
    {
        Auth::guard('resident')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/resident/login');
    }
}
