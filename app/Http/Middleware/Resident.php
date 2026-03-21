<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Use the 'resident' guard
        $user = Auth::guard('resident')->user();

        // Not logged in as resident
        if (!$user) {
            return redirect()->route('resident.login')
                ->with('error', 'Please login to access the resident area.');
        }

        // If user exists but role is not 'resident' (optional safety)
        if (isset($user->role) && $user->role !== 'resident') {
            return redirect()->route('resident.login')
                ->with('error', 'Unauthorized access. Only residents are allowed.');
        }

        // If resident profile is missing
        if (!$user->resident) {
            return redirect()->route('resident.login')
                ->with('error', 'No resident profile found. Please contact the admin.');
        }

        return $next($request);
    }
}
