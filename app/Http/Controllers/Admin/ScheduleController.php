<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyScheduleRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Schedule;
use App\Speaker;
use App\TrainingMaterial;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('schedule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $days = Schedule::with(['speaker', 'materials'])
            ->orderBy('day_number')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_number');

        return view('admin.schedules.index', compact('days'));
    }

    public function toggleComplete(Request $request, Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->is_completed = ! $schedule->is_completed;
        $schedule->completed_at = $schedule->is_completed ? now() : null;
        $schedule->save();

        return response()->json([
            'is_completed' => $schedule->is_completed,
            'id'           => $schedule->id,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('schedule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speakers  = Speaker::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $materials = TrainingMaterial::orderBy('title')->get();

        return view('admin.schedules.create', compact('speakers', 'materials'));
    }

    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create($request->all());

        // Attach any selected training materials
        $schedule->materials()->sync($request->input('materials', []));

        return redirect()->route('admin.schedules.index');
    }

    public function edit(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $speakers  = Speaker::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $materials = TrainingMaterial::orderBy('title')->get();

        $schedule->load(['speaker', 'materials']);

        return view('admin.schedules.edit', compact('speakers', 'schedule', 'materials'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->all());

        // Sync attached training materials
        $schedule->materials()->sync($request->input('materials', []));

        return redirect()->route('admin.schedules.index');
    }

    public function show(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->load('speaker');

        return view('admin.schedules.show', compact('schedule'));
    }

    public function destroy(Schedule $schedule)
    {
        abort_if(Gate::denies('schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $schedule->delete();

        return back();
    }

    public function massDestroy(MassDestroyScheduleRequest $request)
    {
        Schedule::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
