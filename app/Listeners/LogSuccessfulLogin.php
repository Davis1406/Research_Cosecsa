<?php

namespace App\Listeners;

use App\LoginLog;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        LoginLog::create([
            'user_id'      => $event->user->id,
            'ip_address'   => request()->ip(),
            'logged_in_at' => now(),
        ]);
    }
}
