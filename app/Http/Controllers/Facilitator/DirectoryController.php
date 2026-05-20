<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Speaker;

class DirectoryController extends Controller
{
    public function index()
    {
        $speakers = Speaker::with(['user', 'photo'])
            ->withCount(['schedules', 'materials'])
            ->get();

        return view('facilitator.directory.index', compact('speakers'));
    }

    public function show(Speaker $speaker)
    {
        $speaker->load(['user', 'photo', 'schedules', 'materials']);
        return view('facilitator.directory.show', compact('speaker'));
    }
}
