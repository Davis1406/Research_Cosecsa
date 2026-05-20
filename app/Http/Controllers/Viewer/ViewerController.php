<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Speaker;
use App\Trainee;
use App\Schedule;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function dashboard()
    {
        $speakerCount  = Speaker::count();
        $traineeCount  = Trainee::count();
        $sessionCount  = Schedule::count();
        $completedCount = Schedule::where('is_completed', true)->count();

        return view('viewer.dashboard', compact('speakerCount', 'traineeCount', 'sessionCount', 'completedCount'));
    }

    public function facilitators()
    {
        $speakers = Speaker::with('user')
            ->withCount(['schedules', 'materials'])
            ->orderBy('name')
            ->get();

        return view('viewer.facilitators', compact('speakers'));
    }

    public function trainees(Request $request)
    {
        $trainees = Trainee::orderBy('name')->get();

        // Export CSV
        if ($request->query('export') === 'csv') {
            $filename = 'trainees_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            $callback = function () use ($trainees) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Name', 'Email', 'Institution', 'Country', 'Specialty', 'Registration Date']);
                foreach ($trainees as $t) {
                    fputcsv($out, [
                        $t->name,
                        $t->email ?? '',
                        $t->institution ?? '',
                        $t->country ?? '',
                        $t->specialty ?? '',
                        $t->created_at ? $t->created_at->format('Y-m-d') : '',
                    ]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('viewer.trainees', compact('trainees'));
    }

    public function timetable()
    {
        $days = Schedule::with(['speaker', 'materials'])
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');

        return view('viewer.timetable', compact('days'));
    }
}
