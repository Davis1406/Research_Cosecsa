<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRoles = auth()->user()->roles->pluck('title')
            ->map(fn($t) => strtolower(str_replace(' ', '-', $t)))
            ->toArray();

        // Admin / Super Admin bypass ALL role checks — they can do everything
        if (in_array('admin', $userRoles) || in_array('super-admin', $userRoles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if (in_array(strtolower($role), $userRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Access denied.');
    }
}
