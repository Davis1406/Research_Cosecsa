<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTraineeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('trainee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'  => ['required'],
            'email' => ['required', 'email', 'unique:trainees,email'],
        ];
    }
}
