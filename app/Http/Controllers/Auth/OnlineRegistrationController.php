<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnlineRegistrationRequest;
use App\Role;
use App\Trainee;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Public self-registration for the COSECSA Online Research Methodology
 * Course — the link is shared with prospective trainees on the Zoom call,
 * no admin action needed beforehand. Creates a User + Trainee (course_type
 * = online) pair, the same shape Admin/Facilitator "add trainee" already
 * creates, and logs the trainee straight into their new portal.
 */
class OnlineRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function show()
    {
        return view('auth.register-online');
    }

    public function store(StoreOnlineRegistrationRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $traineeRole = Role::where('title', 'Trainee')->first();
        if ($traineeRole) {
            $user->roles()->attach($traineeRole->id);
        }

        Trainee::create([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'course_type'         => 'online',
            'phone'               => $validated['phone'] ?? null,
            'institution'         => $validated['institution'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'country'             => $validated['country'] ?? null,
            'specialty'           => $validated['specialty'] ?? null,
            'enrollment_date'     => now(),
            'user_id'             => $user->id,
        ]);

        Auth::login($user);

        return redirect('/trainee')->with('message', 'Welcome! Your registration for the Online Research Methodology Course is complete.');
    }
}
