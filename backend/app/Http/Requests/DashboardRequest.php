<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => ['nullable', 'integer', Rule::in([7, 30, 90, 365])],
            'date_from' => ['nullable', 'date', 'required_with:date_to'],
            'date_to' => ['nullable', 'date', 'required_with:date_from', 'after_or_equal:date_from'],
            'insurance_type' => ['nullable', 'string', 'max:255'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
