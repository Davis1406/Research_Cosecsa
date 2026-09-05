<?php

namespace App\Http\Requests;

use App\Schedule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'       => ['required'],
            'course_type' => ['nullable', 'in:physical,online'],
            'day_number'  => ['required', 'integer', 'min:1', 'max:365'],
            'start_time'  => ['required', 'date_format:' . config('panel.time_format')],
            'end_time'    => ['nullable', 'date_format:' . config('panel.time_format')],
            'date'        => ['nullable', 'date'],
        ];
    }
}
