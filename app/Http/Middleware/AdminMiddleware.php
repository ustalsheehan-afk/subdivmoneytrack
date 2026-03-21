<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if logged in as Admin
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            
            if ($user->role === 'admin') {
                return $next($request);
            }
            
            if ($user->role === 'resident') {
                return redirect()->route('resident.dashboard');
            }
        }

        // Check if logged in as Resident (but trying to access admin route)
        if (Auth::guard('resident')->check()) {
            $user = Auth::guard('resident')->user();
            
            if ($user->role === 'resident') {
                return redirect()->route('resident.dashboard');
            }
            
            // If they are admin but logged in via resident guard, 
            // we redirect them to admin login to establish the correct session
            if ($user->role === 'admin') {
                 // Optional: We could auto-login here, but redirecting to login is safer/simpler
                 return redirect()->route('admin.login');
            }
        }

        // Not logged in at all -> redirect to login
        return redirect()->route('admin.login');
    }
}
