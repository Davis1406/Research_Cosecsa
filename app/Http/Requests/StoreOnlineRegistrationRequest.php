<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOnlineRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        // Public — anyone with the shared link may register for the online course.
        return true;
    }

    // The institution/specialty "pick or add" selects submit the literal
    // value "__other__" only if a client has JavaScript disabled (normally
    // JS swaps it out for the typed-in text before submit) — treat that as
    // "left blank" rather than storing the sentinel.
    protected function prepareForValidation()
    {
        foreach (['institution', 'specialty'] as $field) {
            if ($this->input($field) === '__other__') {
                $this->merge([$field => null]);
            }
        }
    }

    public function rules()
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'max:255', 'unique:users,email', 'unique:trainees,email'],
            'password'            => ['required', 'string', 'min:8', 'confirmed'],
            'phone'               => ['nullable', 'string', 'max:50'],
            'institution'         => ['nullable', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'country'             => ['nullable', 'string', 'max:100'],
            'specialty'           => ['nullable', 'string', 'max:255'],
        ];
    }
}
