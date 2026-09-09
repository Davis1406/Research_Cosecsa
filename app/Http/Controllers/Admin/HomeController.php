<?php

namespace App\Http\Controllers\Admin;

use App\LoginLog;
use App\Schedule;
use App\Speaker;
use App\Trainee;
use App\TrainingMaterial;

class HomeController
{
    public function index()
    {
        $courseType = course_type();

        // Exclude tea/lunch breaks from session counts — scoped to the
        // currently selected course (Physical/Online switcher).
        $sessionsQuery = Schedule::course($courseType)
                                  ->where('title', 'not like', '%break%')
                                  ->where('title', 'not like', '%tea%');
        $materialsQuery = TrainingMaterial::course($courseType);

        $stats = [
            'trainees'      => Trainee::course($courseType)->count(),
            // Facilitators aren't tied to a single course — they can teach in either.
            'facilitators'  => Speaker::count(),
            'materials'     => (clone $materialsQuery)->count(),
            'sessions'      => (clone $sessionsQuery)->count(),
            'presentations' => (clone $materialsQuery)->where('type', 'presentation')->count(),
            'videos'        => (clone $materialsQuery)->where('type', 'video')->count(),
            'documents'     => (clone $materialsQuery)->where('type', 'document')->count(),
        ];

        $recentTrainees   = Trainee::course($courseType)->latest()->take(5)->get();
        $recentMaterials  = (clone $materialsQuery)->with('facilitator')->latest()->take(5)->get();
        $upcomingSessions = (clone $sessionsQuery)->with('speaker')
                                ->orderBy('day_number')->orderBy('start_time')->take(5)->get();

        // Logins in the last 24 hours, most recent first — system-wide, not course-scoped.
        $recentLogins = LoginLog::with('user')
            ->where('logged_in_at', '>=', now()->subDay())
            ->orderByDesc('logged_in_at')
            ->get();

        return view('admin.home', compact('stats', 'recentTrainees', 'recentMaterials', 'upcomingSessions', 'recentLogins', 'courseType'));
    }
}
