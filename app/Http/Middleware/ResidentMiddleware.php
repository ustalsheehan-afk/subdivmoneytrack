<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if logged in as Resident
        if (Auth::guard('resident')->check()) {
            $user = Auth::guard('resident')->user();

            if ($user->role === 'resident') {
                 // Check if account is active
                if (!$user->active) {
                    Auth::guard('resident')->logout();
                    return redirect()->route('resident.login')
                        ->with('error', 'Your account is not active.');
                }
                return $next($request);
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        // 2. Check if logged in as Admin (but trying to access resident route)
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // If resident logged in via admin guard (unlikely but possible)
            if ($user->role === 'resident') {
                 // Redirect to resident login to establish session
                 return redirect()->route('resident.login');
            }
        }

        // 3. Not logged in -> redirect to login
        return redirect()->route('resident.login')
            ->with('error', 'Please login first.');
    }
}
