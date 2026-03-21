<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::guard('resident')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect to resident dashboard
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
