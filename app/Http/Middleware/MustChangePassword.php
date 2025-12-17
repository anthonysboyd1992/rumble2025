<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            if (!$request->routeIs('force-password-change') && !$request->routeIs('force-password-change.update') && !$request->routeIs('logout')) {
                return redirect()->route('force-password-change');
            }
        }

        return $next($request);
    }
}

