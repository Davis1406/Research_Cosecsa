<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Schedule;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $isLead  = $user->roles->pluck('title')->contains('Lead Facilitator');
        $speaker = $user->speaker;

        $courseType = $request->query('course', config('courses.default'));
        if (!array_key_exists($courseType, config('courses.types'))) {
            $courseType = config('courses.default');
        }

        // Facilitators can teach in either course — switch via the tabs
        $days = Schedule::with(['speaker', 'materials'])
            ->course($courseType)
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');
        $mySpeakerId = $user->speaker?->id;
        return view('facilitator.timetable', compact('days', 'isLead', 'mySpeakerId', 'courseType'));
    }
}
