<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $user = Auth::user();
        if ($user && $user->can('dashboard.view')) {
            return $next($request);
        }

        abort(403, 'Unauthorized access to admin area.');
    }
}
