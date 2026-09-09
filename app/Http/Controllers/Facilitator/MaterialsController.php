<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;
use App\Trainee;
use Illuminate\Http\Request;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $isLead  = $user->roles->pluck('title')->contains('Lead Facilitator');
        $speaker = $user->speaker;

        $courseType = course_type();

        // Facilitators can teach in either course — switched globally via the topbar
        $materials = TrainingMaterial::with('facilitator')
            ->course($courseType)
            ->orderBy('category')
            ->orderBy('title')
            ->get();
        $mySpeakerId = $user->speaker?->id;

        return view('facilitator.materials', compact('materials', 'isLead', 'mySpeakerId', 'courseType'));
    }

    public function trainees()
    {
        // Lead facilitator only
        $traineeList = Trainee::with('documents')->orderBy('name')->get();
        return view('facilitator.trainees', compact('traineeList'));
    }
}
