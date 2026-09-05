<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Schedule;

class TimetableController extends Controller
{
    public function index()
    {
        $trainee    = auth()->user()->trainee;
        $courseType = $trainee->course_type ?? config('courses.default');

        $days = Schedule::with(['speaker', 'materials'])
            ->course($courseType)
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');
        return view('trainee.timetable', compact('days', 'courseType'));
    }
}
