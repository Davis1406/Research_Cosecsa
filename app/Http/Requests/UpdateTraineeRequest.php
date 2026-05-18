<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateTraineeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('trainee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'  => ['required'],
            'email' => ['required', 'email', 'unique:trainees,email,' . $this->trainee->id],
        ];
    }
}
