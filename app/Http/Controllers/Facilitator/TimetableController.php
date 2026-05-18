<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Schedule;

class TimetableController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $isLead  = $user->roles->pluck('title')->contains('Lead Facilitator');
        $speaker = $user->speaker;

        $query = Schedule::with(['speaker', 'materials'])
            ->orderBy('day_number')
            ->orderBy('start_time');

        if (!$isLead && $speaker) {
            $query->where('speaker_id', $speaker->id);
        }

        $days = $query->get()->groupBy('day_number');
        return view('facilitator.timetable', compact('days', 'isLead'));
    }
}
