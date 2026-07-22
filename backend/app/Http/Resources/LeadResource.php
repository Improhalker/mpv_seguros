<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organizationId' => $this->organization_id,
            'assignedUserId' => $this->assigned_user_id,
            'createdBy' => $this->created_by,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'insuranceType' => $this->insurance_type,
            'customInsuranceType' => $this->custom_insurance_type,
            'status' => $this->status,
            'urgency' => $this->urgency,
            'source' => $this->source,
            'sourceDetails' => $this->source_details,
            'generalNotes' => $this->general_notes,
            'birthDate' => $this->birth_date?->toDateString(),
            'city' => $this->city,
            'state' => $this->state,
            'occupation' => $this->occupation,
            'companyName' => $this->company_name,
            'employmentType' => $this->employment_type,
            'incomeRange' => $this->income_range,
            'hasCurrentInsurance' => $this->has_current_insurance,
            'currentInsurer' => $this->current_insurer,
            'currentPolicyExpiresAt' => $this->current_policy_expires_at?->toDateString(),
            'estimatedAssetValue' => $this->estimated_asset_value === null ? null : (float) $this->estimated_asset_value,
            'availableBudget' => $this->available_budget === null ? null : (float) $this->available_budget,
            'needsDescription' => $this->needs_description,
            'preferredContactPeriod' => $this->preferred_contact_period,
            'financialCapacityScore' => $this->financial_capacity_score,
            'salesViabilityScore' => $this->sales_viability_score,
            'interestLevelScore' => $this->interest_level_score,
            'availabilityScore' => $this->availability_score,
            'qualificationScore' => $this->qualification_score === null ? null : (float) $this->qualification_score,
            'lastContactAt' => $this->last_contact_at?->toISOString(),
            'nextContactAt' => $this->next_contact_at?->toISOString(),
            'closedAt' => $this->closed_at?->toISOString(),
            'lostAt' => $this->lost_at?->toISOString(),
            'lossReason' => $this->loss_reason,
            'lossReasonDetails' => $this->loss_reason_details,
            'assignedUser' => $this->whenLoaded('assignedUser', fn (): ?array => $this->assignedUser ? [
                'id' => $this->assignedUser->id,
                'name' => $this->assignedUser->name,
            ] : null),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
