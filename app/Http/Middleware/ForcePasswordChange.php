<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in via resident guard
        if (Auth::guard('resident')->check()) {
            $user = Auth::guard('resident')->user();
        } else {
            $user = Auth::user();
        }

        if ($user && $user->role === 'resident' && $user->must_change_password) {
            // Allow access to change password routes and logout
            if (!$request->routeIs('resident.change-password') && 
                !$request->routeIs('resident.change-password.update') &&
                !$request->routeIs('logout')) {
                return redirect()->route('resident.change-password');
            }
        }

        return $next($request);
    }
}
