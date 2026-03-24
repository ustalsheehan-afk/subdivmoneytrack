<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'resident') {
            if (!Auth::user()->active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is not active.');
            }
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Redirected to Admin Dashboard.');
        }

        return redirect()->route('login')->with('error', 'Please login to access Resident Portal.');
    }
}
