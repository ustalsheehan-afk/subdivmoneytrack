<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permissionKey)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login.');
        }

        $user = $request->user();
        if (!$user || !$user->can($permissionKey)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}

