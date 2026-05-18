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

        if ($isLead) {
            $materials = TrainingMaterial::with('facilitator')
                ->orderBy('category')
                ->orderBy('title')
                ->get();
        } else {
            $materials = $speaker
                ? TrainingMaterial::where('speaker_id', $speaker->id)->orderBy('title')->get()
                : collect();
        }

        return view('facilitator.materials', compact('materials', 'isLead'));
    }

    public function trainees()
    {
        // Lead facilitator only
        $traineeList = Trainee::with('documents')->orderBy('name')->get();
        return view('facilitator.trainees', compact('traineeList'));
    }
}
