<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTraineeRequest;
use App\Http\Requests\StoreTraineeRequest;
use App\Http\Requests\UpdateTraineeRequest;
use App\Trainee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraineesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('trainee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType = course_type();
        $trainees   = Trainee::course($courseType)->orderBy('id', 'desc')->get();

        return view('admin.trainees.index', compact('trainees', 'courseType'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('trainee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType = course_type();

        return view('admin.trainees.create', compact('courseType'));
    }

    public function store(StoreTraineeRequest $request)
    {
        Trainee::create($request->all());

        return redirect()->route('admin.trainees.index')
            ->with('message', 'Trainee registered successfully.');
    }

    public function edit(Trainee $trainee)
    {
        abort_if(Gate::denies('trainee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $courseType = $trainee->course_type;

        return view('admin.trainees.edit', compact('trainee', 'courseType'));
    }

    public function update(UpdateTraineeRequest $request, Trainee $trainee)
    {
        $trainee->update($request->all());

        return redirect()->route('admin.trainees.index')
            ->with('message', 'Trainee updated successfully.');
    }

    public function show(Trainee $trainee)
    {
        abort_if(Gate::denies('trainee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainee->load([
            'documents' => fn($q) => $q->with('comments.user', 'reviewers')->latest(),
        ]);

        return view('admin.trainees.show', compact('trainee'));
    }

    public function destroy(Trainee $trainee)
    {
        abort_if(Gate::denies('trainee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainee->delete();

        return back()->with('message', 'Trainee removed successfully.');
    }

    public function massDestroy(MassDestroyTraineeRequest $request)
    {
        Trainee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
