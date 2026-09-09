<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;

class MaterialsController extends Controller
{
    public function index()
    {
        $courseType = course_type();
        $materials  = TrainingMaterial::with('facilitator')
            ->course($courseType)
            ->orderBy('category')
            ->orderBy('title')
            ->get();

        return view('viewer.materials', compact('materials', 'courseType'));
    }
}
