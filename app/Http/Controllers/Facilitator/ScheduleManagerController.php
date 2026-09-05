<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\Speaker;
use App\TrainingMaterial;
use Illuminate\Http\Request;

class ScheduleManagerController extends Controller
{
    /**
     * Resolve and validate the ?course= query param, falling back to the configured default.
     */
    private function resolveCourseType(Request $request)
    {
        $courseType = $request->query('course', config('courses.default'));

        return array_key_exists($courseType, config('courses.types')) ? $courseType : config('courses.default');
    }

    public function index(Request $request)
    {
        $courseType = $this->resolveCourseType($request);

        $days = Schedule::with(['speaker', 'materials'])
            ->course($courseType)
            ->orderBy('day_number')->orderBy('start_time')->get()
            ->groupBy('day_number');
        return view('facilitator.schedule-manager.index', compact('days', 'courseType'));
    }

    public function create(Request $request)
    {
        $courseType = $this->resolveCourseType($request);
        $speakers   = Speaker::orderBy('name')->get();
        $materials  = TrainingMaterial::course($courseType)->orderBy('title')->get();
        $maxDay     = Schedule::course($courseType)->max('day_number') ?? 0;
        return view('facilitator.schedule-manager.form', [
            'session'    => null,
            'speakers'   => $speakers,
            'materials'  => $materials,
            'maxDay'     => $maxDay,
            'courseType' => $courseType,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'course_type' => 'nullable|in:physical,online',
            'day_number'  => 'required|integer|min:1',
            'date'        => 'nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i',
            'location'    => 'nullable|string|max:255',
            'speaker_id'  => 'nullable|exists:speakers,id',
            'materials'   => 'nullable|array',
            'materials.*' => 'exists:training_materials,id',
        ]);

        $session = Schedule::create([
            'title'       => $validated['title'],
            'subtitle'    => $validated['subtitle'] ?? null,
            'course_type' => $validated['course_type'] ?? config('courses.default'),
            'day_number'  => $validated['day_number'],
            'date'        => $validated['date'] ?? null,
            'start_time'  => $validated['start_time'] ?? null,
            'end_time'    => $validated['end_time'] ?? null,
            'location'    => $validated['location'] ?? null,
            'speaker_id'  => $validated['speaker_id'] ?? null,
        ]);

        $session->materials()->sync($request->input('materials', []));

        return redirect()->route('facilitator.schedule-manager.index', ['course' => $session->course_type])
                         ->with('message', 'Session created successfully.');
    }

    public function edit(Schedule $session)
    {
        $session->load(['speaker', 'materials']);
        $courseType = $session->course_type;
        $speakers   = Speaker::orderBy('name')->get();
        $materials  = TrainingMaterial::course($courseType)->orderBy('title')->get();
        return view('facilitator.schedule-manager.form', compact('session', 'speakers', 'materials', 'courseType'));
    }

    public function update(Request $request, Schedule $session)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'course_type' => 'nullable|in:physical,online',
            'day_number'  => 'required|integer|min:1',
            'date'        => 'nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i',
            'location'    => 'nullable|string|max:255',
            'speaker_id'  => 'nullable|exists:speakers,id',
            'materials'   => 'nullable|array',
            'materials.*' => 'exists:training_materials,id',
        ]);

        $session->update([
            'title'       => $validated['title'],
            'subtitle'    => $validated['subtitle'] ?? null,
            'course_type' => $validated['course_type'] ?? $session->course_type,
            'day_number'  => $validated['day_number'],
            'date'        => $validated['date'] ?? null,
            'start_time'  => $validated['start_time'] ?? null,
            'end_time'    => $validated['end_time'] ?? null,
            'location'    => $validated['location'] ?? null,
            'speaker_id'  => $validated['speaker_id'] ?? null,
        ]);

        $session->materials()->sync($request->input('materials', []));

        return redirect()->route('facilitator.schedule-manager.index', ['course' => $session->course_type])
                         ->with('message', 'Session updated successfully.');
    }

    public function destroy(Schedule $session)
    {
        $session->materials()->detach();
        $session->delete();
        return redirect()->route('facilitator.schedule-manager.index')
                         ->with('message', 'Session deleted.');
    }

    public function toggleComplete(Request $request, Schedule $session)
    {
        $session->update(['is_completed' => !$session->is_completed]);
        return back()->with('message', 'Session status updated.');
    }
}
