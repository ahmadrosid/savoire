<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminEmails = explode(',', env('ADMIN_EMAILS', ''));
        
        if (!Auth::check() || !in_array(Auth::user()->email, $adminEmails)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
