<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return LeadRules::rules(false, $this->input('status'), $this->input('loss_reason'));
    }

    protected function prepareForValidation(): void
    {
        $this->merge(LeadRules::normalized($this->all()));
    }
}
