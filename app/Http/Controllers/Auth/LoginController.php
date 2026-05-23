<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if (!$user) {
            return '/login';
        }
        $roles = $user->roles->pluck('title')
            ->map(fn($t) => strtolower(str_replace(' ', '-', $t)))
            ->toArray();
        if (in_array('trainee', $roles)) {
            return '/trainee';
        }
        if (in_array('facilitator', $roles) || in_array('lead-facilitator', $roles)) {
            return '/facilitator';
        }
        if (in_array('viewer', $roles)) {
            return '/viewer';
        }
        return '/admin';
    }
}
