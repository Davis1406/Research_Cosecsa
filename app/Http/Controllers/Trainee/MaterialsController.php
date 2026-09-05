<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;

class MaterialsController extends Controller
{
    public function index()
    {
        $trainee    = auth()->user()->trainee;
        $courseType = $trainee->course_type ?? config('courses.default');

        $materials = TrainingMaterial::with('facilitator')
            ->course($courseType)
            ->orderBy('category')
            ->orderBy('title')
            ->get();
        return view('trainee.materials', compact('materials', 'courseType'));
    }
}
