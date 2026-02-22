<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaiterRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Jina la kwanza linahitajika.',
            'last_name.required' => 'Jina la mwisho linahitajika.',
            'email.required' => 'Barua pepe inahitajika.',
            'email.unique' => 'Barua pepe tayari imesajiliwa.',
            'phone.required' => 'Nambari ya simu inahitajika.',
            'password.required' => 'Neno la siri linahitajika.',
            'password.min' => 'Neno la siri lazima liwe na angalau herufi 8.',
            'password.confirmed' => 'Thibitisha neno la siri halilingani.',
        ];
    }
}
