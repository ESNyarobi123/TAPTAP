<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->restaurant_id !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'period_month' => ['required', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['required', 'numeric', 'min:0'],
            'paye' => ['required', 'numeric', 'min:0'],
            'nssf' => ['required', 'numeric', 'min:0'],
        ];
    }
}
