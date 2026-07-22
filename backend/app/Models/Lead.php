<?php

namespace App\Models;

use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'organization_id', 'assigned_user_id', 'created_by', 'name', 'phone', 'email',
    'insurance_type', 'custom_insurance_type', 'status', 'urgency', 'source',
    'source_details', 'general_notes', 'birth_date', 'city', 'state', 'occupation',
    'company_name', 'employment_type', 'income_range', 'has_current_insurance',
    'current_insurer', 'current_policy_expires_at', 'estimated_asset_value',
    'available_budget', 'needs_description', 'preferred_contact_period',
    'financial_capacity_score', 'sales_viability_score', 'interest_level_score',
    'availability_score', 'qualification_score', 'last_contact_at', 'next_contact_at',
    'closed_at', 'lost_at', 'loss_reason', 'loss_reason_details',
])]
class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(LeadInteraction::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'current_policy_expires_at' => 'date',
            'has_current_insurance' => 'boolean',
            'estimated_asset_value' => 'decimal:2',
            'available_budget' => 'decimal:2',
            'qualification_score' => 'decimal:1',
            'last_contact_at' => 'datetime',
            'next_contact_at' => 'datetime',
            'closed_at' => 'datetime',
            'lost_at' => 'datetime',
        ];
    }
}
