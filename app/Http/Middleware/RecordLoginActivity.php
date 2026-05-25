<?php

namespace App\Http\Middleware;

use App\LoginLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RecordLoginActivity
{
    /**
     * Record a login_log entry for the current user if they don't already
     * have one within the last 24 hours. This catches users whose session
     * predates the login_logs table, as well as "remember me" sessions.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId   = Auth::id();
            $cacheKey = 'login_recorded_' . $userId;

            // Cache flag lasts 23 hours — cheaper than a DB hit on every request
            if (!Cache::has($cacheKey)) {
                $exists = LoginLog::where('user_id', $userId)
                    ->where('logged_in_at', '>=', now()->subDay())
                    ->exists();

                if (!$exists) {
                    LoginLog::create([
                        'user_id'      => $userId,
                        'ip_address'   => $request->ip(),
                        'logged_in_at' => now(),
                    ]);
                }

                // Whether we inserted or not, don't recheck for 23 hours
                Cache::put($cacheKey, true, now()->addHours(23));
            }
        }

        return $next($request);
    }
}
