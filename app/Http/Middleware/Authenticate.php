<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // If the request is for admin routes
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            // If the request is for resident routes
            if ($request->is('resident') || $request->is('resident/*')) {
                return route('resident.login');
            }

            // Optional fallback
            return '/login';
        }

        return null;
    }
}
