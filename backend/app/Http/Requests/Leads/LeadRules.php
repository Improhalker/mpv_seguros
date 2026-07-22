<?php

namespace App\Http\Requests\Leads;

use Illuminate\Validation\Rule;

class LeadRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules(bool $isCreating, ?string $status, ?string $lossReason): array
    {
        $required = $isCreating ? 'required' : 'sometimes';

        return [
            'name' => [$required, 'string', 'max:255'],
            'phone' => [$required, 'string', 'min:10', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'insurance_type' => [$required, Rule::in(self::insuranceTypes())],
            'custom_insurance_type' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn (): bool => $status === 'outro')],
            'status' => ['sometimes', Rule::in(self::statuses())],
            'urgency' => ['nullable', Rule::in(['baixa', 'media', 'alta'])],
            'source' => ['nullable', Rule::in(self::sources())],
            'source_details' => ['nullable', 'string', 'max:255'],
            'general_notes' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'size:2'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', Rule::in(self::employmentTypes())],
            'income_range' => ['nullable', Rule::in(self::incomeRanges())],
            'has_current_insurance' => ['nullable', 'boolean'],
            'current_insurer' => ['nullable', 'string', 'max:255'],
            'current_policy_expires_at' => ['nullable', 'date'],
            'estimated_asset_value' => ['nullable', 'numeric', 'min:0'],
            'available_budget' => ['nullable', 'numeric', 'min:0'],
            'needs_description' => ['nullable', 'string'],
            'preferred_contact_period' => ['nullable', Rule::in(['manha', 'tarde', 'noite', 'indiferente'])],
            'financial_capacity_score' => ['nullable', 'integer', 'between:1,5'],
            'sales_viability_score' => ['nullable', 'integer', 'between:1,5'],
            'interest_level_score' => ['nullable', 'integer', 'between:1,5'],
            'availability_score' => ['nullable', 'integer', 'between:1,5'],
            'next_contact_at' => ['nullable', 'date'],
            'assigned_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'loss_reason' => ['nullable', Rule::in(self::lossReasons()), Rule::requiredIf(fn (): bool => $status === 'perdido')],
            'loss_reason_details' => ['nullable', 'string', Rule::requiredIf(fn (): bool => $status === 'perdido' && $lossReason === 'outro')],
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public static function normalized(array $attributes): array
    {
        $normalized = [];

        if (array_key_exists('phone', $attributes) && is_string($attributes['phone'])) {
            $normalized['phone'] = preg_replace('/\D+/', '', $attributes['phone']);
        }

        foreach (self::nullableFields() as $field) {
            if (array_key_exists($field, $attributes) && $attributes[$field] === '') {
                $normalized[$field] = null;
            }
        }

        return $normalized;
    }

    /** @return array<int, string> */
    public static function statuses(): array
    {
        return ['novo', 'contatado', 'em_negociacao', 'aguardando_cliente', 'fechado', 'perdido'];
    }

    /** @return array<int, string> */
    private static function insuranceTypes(): array
    {
        return ['auto', 'residencial', 'vida', 'saude', 'empresarial', 'viagem', 'previdencia', 'outro'];
    }

    /** @return array<int, string> */
    private static function sources(): array
    {
        return ['indicacao', 'whatsapp', 'instagram', 'site', 'ligacao', 'cliente_existente', 'anuncio', 'prospeccao', 'outro'];
    }

    /** @return array<int, string> */
    private static function employmentTypes(): array
    {
        return ['clt', 'autonomo', 'empresario', 'servidor_publico', 'aposentado', 'desempregado', 'outro'];
    }

    /** @return array<int, string> */
    private static function incomeRanges(): array
    {
        return ['ate_2000', '2001_5000', '5001_10000', '10001_20000', 'acima_20000', 'nao_informado'];
    }

    /** @return array<int, string> */
    private static function lossReasons(): array
    {
        return ['sem_interesse', 'preco', 'sem_capacidade_financeira', 'fechou_com_concorrente', 'nao_respondeu', 'dados_invalidos', 'fora_do_perfil', 'outro'];
    }

    /** @return array<int, string> */
    private static function nullableFields(): array
    {
        return [
            'email', 'custom_insurance_type', 'urgency', 'source', 'source_details', 'general_notes', 'birth_date', 'city', 'state',
            'occupation', 'company_name', 'employment_type', 'income_range', 'has_current_insurance', 'current_insurer',
            'current_policy_expires_at', 'estimated_asset_value', 'available_budget', 'needs_description', 'preferred_contact_period',
            'financial_capacity_score', 'sales_viability_score', 'interest_level_score', 'availability_score', 'next_contact_at',
            'assigned_user_id', 'loss_reason', 'loss_reason_details',
        ];
    }
}
