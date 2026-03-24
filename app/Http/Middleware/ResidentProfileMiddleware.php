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
        $user = Auth::user();

        // If not logged in, redirect to login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // If not a resident, redirect to login
        if ($user->role !== 'resident') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        // If resident profile is missing
        if (!$user->resident) {
            return redirect()->route('resident.profile.edit')->with('error', 'Resident profile not found. Please complete your profile.');
        }

        return $next($request);
    }
}
