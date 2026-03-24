<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role === 'resident') {
            return redirect()->route('resident.dashboard')->with('error', 'Unauthorized access to admin area.');
        }

        return redirect()->route('login')->with('error', 'Please login as administrator.');
    }
}
