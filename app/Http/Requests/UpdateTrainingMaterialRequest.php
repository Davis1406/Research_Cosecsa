<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateTrainingMaterialRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('training_material_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required'],
            'type'  => ['required'],
        ];
    }
}
