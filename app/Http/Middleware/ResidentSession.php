<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResidentSession
{
    public function handle(Request $request, Closure $next)
    {
        // Set custom session cookie for residents
        config(['session.cookie' => 'resident_session']);
        return $next($request);
    }
}
