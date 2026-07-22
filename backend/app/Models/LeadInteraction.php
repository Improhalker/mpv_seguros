<?php

namespace App\Models;

use Database\Factories\LeadInteractionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lead_id', 'user_id', 'type', 'description', 'occurred_at', 'next_contact_at'])]
class LeadInteraction extends Model
{
    /** @use HasFactory<LeadInteractionFactory> */
    use HasFactory, HasUuids;

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'next_contact_at' => 'datetime',
        ];
    }
}
