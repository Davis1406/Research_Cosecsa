<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\Trainee;
use App\TrainingMaterial;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $speaker = $user->speaker;
        $isLead  = $user->roles->pluck('title')->contains('Lead Facilitator');
        $mySessions     = $speaker ? Schedule::where('speaker_id', $speaker->id)
                                              ->where('title', 'not like', '%break%')
                                              ->where('title', 'not like', '%lunch%')
                                              ->where('title', 'not like', '%tea%')
                                              ->count() : 0;
        $myMaterials    = $speaker ? TrainingMaterial::where('speaker_id', $speaker->id)->count() : 0;
        // "Total" figures reflect the currently selected course (Physical/Online
        // switcher) — mySessions/myMaterials stay unscoped since a facilitator
        // can teach in either course.
        $courseType     = course_type();
        $totalMaterials = TrainingMaterial::course($courseType)->count();
        $totalTrainees  = Trainee::course($courseType)->count();
        return view('facilitator.dashboard', compact('speaker', 'isLead', 'mySessions', 'myMaterials', 'totalMaterials', 'totalTrainees', 'courseType'));
    }
}
