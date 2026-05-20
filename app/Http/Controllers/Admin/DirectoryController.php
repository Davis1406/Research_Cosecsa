<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Speaker;

class DirectoryController extends Controller
{
    public function index()
    {
        $speakers = Speaker::with('user')
            ->withCount(['schedules', 'materials'])
            ->get();

        return view('admin.directory.index', compact('speakers'));
    }

    public function show(Speaker $speaker)
    {
        $speaker->load(['user', 'schedules.materials', 'materials']);
        return view('admin.directory.show', compact('speaker'));
    }
}
