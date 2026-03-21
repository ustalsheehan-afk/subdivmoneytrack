<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSession
{
    public function handle(Request $request, Closure $next)
    {
        // Set custom session cookie for admin
        config(['session.cookie' => 'admin_session']);
        return $next($request);
    }
}
