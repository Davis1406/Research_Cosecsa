<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnlineRegistrationRequest;
use App\Mail\OnlineRegistrationConfirmation;
use App\Role;
use App\Services\CosecsaApiClient;
use App\Trainee;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        return view('auth.register-online', [
            'programmes' => $this->fetchReferenceList('reference/programmes', 'programmes', 'name'),
            // cosecsa-api's ReferenceController::hospitals() has a bug where
            // per_page=all 500s (Request::string() returns a Stringable,
            // which the controller's `=== 'all'` check then fails, falling
            // through to `(int) $perPage`) — request a page bigger than the
            // hospital count instead of relying on that "all" mode.
            'hospitals'  => $this->fetchReferenceList('reference/hospitals', 'data', 'name', ['per_page' => 1000]),
            'countries'  => config('countries'),
        ]);
    }

    // Pulls a lookup list from cosecsa-api for the form's dropdowns, cached
    // for 30 minutes. This is a public, unauthenticated registration page —
    // it must never fail to load because cosecsa-api is slow or down, so
    // any error just falls back to an empty list (the "Other" / add-new
    // option in the dropdown still lets someone type their own).
    private function fetchReferenceList(string $path, string $dataKey, string $nameKey, array $query = []): array
    {
        return Cache::remember("register-online.$path", 1800, function () use ($path, $dataKey, $nameKey, $query) {
            try {
                $response = (new CosecsaApiClient())->get($path, $query);

                if (! $response->successful()) {
                    Log::warning("cosecsa-api $path returned {$response->status()} while loading the online registration form");
                    return [];
                }

                return collect($response->json($dataKey, []))
                    ->pluck($nameKey)
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning("cosecsa-api $path unreachable while loading the online registration form: " . $e->getMessage());
                return [];
            }
        });
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

        // Best-effort — a mail outage should never block someone from
        // completing registration; they're already logged in regardless.
        try {
            Mail::to($user->email)->send(new OnlineRegistrationConfirmation($user));
        } catch (\Throwable $e) {
            Log::warning('Online registration confirmation email failed to send: ' . $e->getMessage(), ['user_id' => $user->id]);
        }

        Auth::login($user);

        return redirect('/trainee')->with('message', 'Welcome! Your registration for the Online Research Methodology Course is complete. A confirmation email has been sent to ' . $user->email . '.');
    }
}
