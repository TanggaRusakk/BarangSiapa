<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: middleware 'role:admin' or 'role:admin,vendor'
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $userRole = auth()->user()->role ?? null;
        if (!$roles) {
            return $next($request);
        }

        $allowed = array_map('trim', explode(',', $roles));
        if (!in_array($userRole, $allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
