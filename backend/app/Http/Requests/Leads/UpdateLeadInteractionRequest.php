<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadInteractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', Rule::in(['ligacao', 'whatsapp', 'email', 'reuniao', 'observacao', 'proposta_enviada', 'documento_recebido', 'mudanca_status'])],
            'description' => ['sometimes', 'string'],
            'occurred_at' => ['sometimes', 'date'],
            'next_contact_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('next_contact_at') === '') {
            $this->merge(['next_contact_at' => null]);
        }
    }
}
