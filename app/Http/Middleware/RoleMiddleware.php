<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $roles)
    {
        // Split comma-separated roles and trim spaces
        $allowed = array_map('trim', explode(';', $roles));

        // Get the current user's role
        $userRole = trim(Auth::user()->user_role ?? '');
        if (!Auth::check() || !in_array($userRole, $allowed)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
