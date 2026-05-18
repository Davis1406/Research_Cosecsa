<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;
use App\Trainee;

class MaterialsController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $isLead  = $user->roles->pluck('title')->contains('Lead Facilitator');
        $speaker = $user->speaker;

        // All facilitators see all materials; isLead used by the view for any lead-only UI
        $materials = TrainingMaterial::with('facilitator')
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        return view('facilitator.materials', compact('materials', 'isLead'));
    }

    public function trainees()
    {
        // Lead facilitator only
        $traineeList = Trainee::with('documents')->orderBy('name')->get();
        return view('facilitator.trainees', compact('traineeList'));
    }
}
