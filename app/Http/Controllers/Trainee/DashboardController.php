<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\TrainingMaterial;

class DashboardController extends Controller
{
    public function index()
    {
        $trainee = auth()->user()->trainee;
        $totalSessions = Schedule::count();
        $completedSessions = Schedule::where('is_completed', true)->count();
        $totalMaterials = TrainingMaterial::count();
        $myDocuments = $trainee ? $trainee->documents()->count() : 0;
        return view('trainee.dashboard', compact('trainee', 'totalSessions', 'completedSessions', 'totalMaterials', 'myDocuments'));
    }
}
