<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkWaiterRequest extends FormRequest
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
            'employment_type' => ['required', 'string', 'in:permanent,temporary'],
            'linked_until' => ['required_if:employment_type,temporary', 'nullable', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employment_type.required' => 'Chagua aina: Muda mrefu (permanent) au Muda (show-time).',
            'employment_type.in' => 'Aina ni permanent au temporary.',
            'linked_until.required_if' => 'Tarehe ya mwisho inahitajika kwa waiter wa muda (show-time).',
            'linked_until.after_or_equal' => 'Tarehe ya mwisho lazima iwe leo au baadaye.',
        ];
    }
}
