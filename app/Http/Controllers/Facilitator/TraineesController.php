<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Trainee;
use App\User;
use App\Role;
use App\Schedule;
use App\Quiz;
use App\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TraineesController extends Controller
{
    /**
     * Resolve and validate the ?course= query param, falling back to the configured default.
     */
    private function resolveCourseType(Request $request)
    {
        $courseType = $request->query('course', config('courses.default'));

        return array_key_exists($courseType, config('courses.types')) ? $courseType : config('courses.default');
    }

    public function index(Request $request)
    {
        $courseType  = $this->resolveCourseType($request);
        $traineeList = Trainee::with([
            'documents' => fn($q) => $q->with('reviewers'),
            'user',
        ])->course($courseType)->latest()->get();

        $totalSessions = Schedule::course($courseType)->count();
        $completedSessions = Schedule::course($courseType)->where('is_completed', true)->count();
        $quizCount = Quiz::where('is_published', true)->count();

        return view('facilitator.trainees', compact('traineeList', 'totalSessions', 'completedSessions', 'quizCount', 'courseType'));
    }

    public function create(Request $request)
    {
        $courseType = $this->resolveCourseType($request);
        return view('facilitator.trainees-form', ['trainee' => null, 'traineeUser' => null, 'courseType' => $courseType]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|string|min:8|confirmed',
            'course_type'         => 'nullable|in:physical,online',
            'phone'               => 'nullable|string|max:50',
            'institution'         => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'country'             => 'nullable|string|max:100',
            'specialty'           => 'nullable|string|max:255',
            'enrollment_date'     => 'nullable|date',
            'notes'               => 'nullable|string|max:1000',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $traineeRole = Role::where('title', 'Trainee')->first();
        if ($traineeRole) {
            $user->roles()->attach($traineeRole->id);
        }

        $trainee = Trainee::create([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'course_type'         => $validated['course_type'] ?? config('courses.default'),
            'phone'               => $validated['phone'] ?? null,
            'institution'         => $validated['institution'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'country'             => $validated['country'] ?? null,
            'specialty'           => $validated['specialty'] ?? null,
            'enrollment_date'     => $validated['enrollment_date'] ?? null,
            'notes'               => $validated['notes'] ?? null,
            'user_id'             => $user->id,
        ]);

        return redirect()->route('facilitator.trainees', ['course' => $trainee->course_type])
            ->with('message', 'Trainee account created successfully.');
    }

    public function edit($id)
    {
        $trainee = Trainee::with('user')->findOrFail($id);
        return view('facilitator.trainees-form', ['trainee' => $trainee, 'traineeUser' => $trainee->user, 'courseType' => $trainee->course_type]);
    }

    public function update(Request $request, $id)
    {
        $trainee = Trainee::with('user')->findOrFail($id);
        $user    = $trainee->user;

        $rules = [
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . ($user ? $user->id : 0),
            'course_type'         => 'nullable|in:physical,online',
            'phone'               => 'nullable|string|max:50',
            'institution'         => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'country'             => 'nullable|string|max:100',
            'specialty'           => 'nullable|string|max:255',
            'enrollment_date'     => 'nullable|date',
            'notes'               => 'nullable|string|max:1000',
            'password'            => 'nullable|string|min:8|confirmed',
        ];
        $validated = $request->validate($rules);

        if ($user) {
            $userUpdate = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userUpdate['password'] = Hash::make($validated['password']);
            }
            $user->update($userUpdate);
        }

        $trainee->update([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'course_type'         => $validated['course_type'] ?? $trainee->course_type,
            'phone'               => $validated['phone'] ?? null,
            'institution'         => $validated['institution'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'country'             => $validated['country'] ?? null,
            'specialty'           => $validated['specialty'] ?? null,
            'enrollment_date'     => $validated['enrollment_date'] ?? null,
            'notes'               => $validated['notes'] ?? null,
        ]);

        return redirect()->route('facilitator.trainees', ['course' => $trainee->course_type])
            ->with('message', 'Trainee updated successfully.');
    }

    public function destroy($id)
    {
        $trainee = Trainee::with('user')->findOrFail($id);
        if ($trainee->user) {
            $trainee->user->delete();
        }
        $trainee->delete();
        return redirect()->route('facilitator.trainees')->with('message', 'Trainee removed.');
    }
}
