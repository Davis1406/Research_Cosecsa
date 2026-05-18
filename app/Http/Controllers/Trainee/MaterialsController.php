<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\TrainingMaterial;

class MaterialsController extends Controller
{
    public function index()
    {
        $materials = TrainingMaterial::with('facilitator')
            ->orderBy('category')
            ->orderBy('title')
            ->get();
        return view('trainee.materials', compact('materials'));
    }
}
