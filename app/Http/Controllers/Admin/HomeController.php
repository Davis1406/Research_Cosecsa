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
        $stats = [
            'trainees'      => Trainee::count(),
            'facilitators'  => Speaker::count(),
            'materials'     => TrainingMaterial::count(),
            'sessions'      => Schedule::count(),
            'presentations' => TrainingMaterial::where('type', 'presentation')->count(),
            'videos'        => TrainingMaterial::where('type', 'video')->count(),
            'documents'     => TrainingMaterial::where('type', 'document')->count(),
        ];

        $recentTrainees   = Trainee::latest()->take(5)->get();
        $recentMaterials  = TrainingMaterial::with('facilitator')->latest()->take(5)->get();
        $upcomingSessions = Schedule::with('speaker')->orderBy('day_number')->orderBy('start_time')->take(5)->get();

        // Logins in the last 24 hours, most recent first
        $recentLogins = LoginLog::with('user')
            ->where('logged_in_at', '>=', now()->subDay())
            ->orderByDesc('logged_in_at')
            ->get();

        return view('admin.home', compact('stats', 'recentTrainees', 'recentMaterials', 'upcomingSessions', 'recentLogins'));
    }
}
