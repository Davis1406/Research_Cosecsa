<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTrainingMaterialRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('training_material_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'       => ['required'],
            'type'        => ['required'],
            'course_type' => ['nullable', 'in:physical,online'],
        ];
    }
}
