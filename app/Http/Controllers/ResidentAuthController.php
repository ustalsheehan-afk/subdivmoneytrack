<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResidentAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('resident')->check()) {
            $user = Auth::guard('resident')->user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('resident.dashboard');
        }
        
        // Also check admin guard to prevent login loop if they are already an admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.resident-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('resident')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('resident')->user();

            if (!$user->active) {
                Auth::guard('resident')->logout();
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact the administrator.',
                ]);
            }

            if ($user->role === 'admin') {
                // If admin tries to login via resident login, redirect them to admin dashboard
                // BUT, they are logged in via 'resident' guard.
                // AdminMiddleware might block them if it checks 'admin' guard.
                // Since we want them to use the Admin Portal, maybe we should logout and redirect?
                // OR: Since we aligned the cookies to be separate, they are technically logged in as a "resident" session.
                // AdminMiddleware (my updated version) redirects resident-guard users to resident.dashboard.
                // This creates a conflict if the user IS an admin but logged in via resident guard.
                
                // Let's force them to admin login if they are an admin.
                Auth::guard('resident')->logout();
                return redirect()->route('admin.login')->withErrors(['email' => 'Admins should login via the Admin Portal.']);
            }

            return redirect()->intended(route('resident.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('resident')->logout();
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('resident.login');
    }

    public function showChangePasswordForm()
    {
        return view('auth.resident-change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::guard('resident')->user();
        // Fallback for multi-guard usage
        if (!$user) $user = Auth::user(); 
        
        /** @var \App\Models\User $user */
        $user->forceFill([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ])->save();

        return redirect()->route('resident.dashboard')->with('status', 'Password changed successfully!');
    }
}
