<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListLeadsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(LeadRules::statuses())],
            'insurance_type' => ['nullable', 'string', 'max:255'],
            'urgency' => ['nullable', Rule::in(['baixa', 'media', 'alta'])],
            'source' => ['nullable', 'string', 'max:255'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'contact_period' => ['nullable', Rule::in(['overdue', 'today', 'upcoming'])],
            'min_score' => ['nullable', 'numeric', 'between:0,10'],
            'max_score' => ['nullable', 'numeric', 'between:0,10', 'gte:min_score'],
            'sort_by' => ['nullable', Rule::in(['name', 'status', 'insurance_type', 'urgency', 'qualification_score', 'next_contact_at', 'created_at'])],
            'sort_direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'between:1,100'],
        ];
    }
}
