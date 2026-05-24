<?php

namespace App\Http\Middleware;

use Closure;

class ForcePasswordChange
{
    /**
     * Redirect authenticated users who still have the default password
     * to the change-password page before they can access anything else.
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow the change-password page and logout through — everything else is blocked
            if (!$request->is('change-password') && !$request->is('logout')) {
                return redirect()->route('change-password.show')
                    ->with('warning', 'You must set a new password before continuing.');
            }
        }

        return $next($request);
    }
}
