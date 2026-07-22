<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadInteractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['ligacao', 'whatsapp', 'email', 'reuniao', 'observacao', 'proposta_enviada', 'documento_recebido', 'mudanca_status'])],
            'description' => ['required', 'string'],
            'occurred_at' => ['required', 'date'],
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
