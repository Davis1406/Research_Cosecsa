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
