<?php

namespace App\Http\Controllers;

use App\TrainingMaterial;

class MaterialViewerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(TrainingMaterial $material)
    {
        $material->load(['facilitator', 'schedules.speaker']);

        $fileUrl = null;
        if ($material->external_url) {
            $fileUrl = $material->external_url;
        } elseif ($material->file) {
            $fileUrl = $material->file->url;
        }

        return view('material-viewer', compact('material', 'fileUrl'));
    }
}
