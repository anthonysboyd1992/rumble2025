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
            $allowedRoutes = [
                'force-password-change',
                'logout',
            ];
            
            // Allow Livewire requests
            if ($request->is('livewire/*') || $request->routeIs('livewire.*')) {
                return $next($request);
            }
            
            if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                return redirect()->route('force-password-change');
            }
        }

        return $next($request);
    }
}

