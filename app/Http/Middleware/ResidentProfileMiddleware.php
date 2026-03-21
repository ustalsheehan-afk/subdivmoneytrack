<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentProfileMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('resident')->user();

        // If not logged in as resident, redirect to login
        if (!$user) {
            return redirect()->route('resident.login')->with('error', 'Please login first.');
        }

        // If resident profile is missing
        if (!$user->resident) {
             // Depending on logic, maybe redirect to contact admin or allow basic access?
             // For now, we'll assume every resident user MUST have a profile.
            return redirect()->route('resident.login')->with('error', 'Resident profile not found. Please contact admin.');
        }

        return $next($request);
    }
}
