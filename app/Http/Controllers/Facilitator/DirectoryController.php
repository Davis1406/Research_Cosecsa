<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Speaker;

class DirectoryController extends Controller
{
    public function index()
    {
        // 'photo' is a Spatie Media accessor, not a relation — don't use with()
        $speakers = Speaker::with('user')
            ->withCount(['schedules', 'materials'])
            ->get();

        return view('facilitator.directory.index', compact('speakers'));
    }

    public function show(Speaker $speaker)
    {
        $speaker->load(['user', 'schedules.materials', 'materials']);
        return view('facilitator.directory.show', compact('speaker'));
    }
}
