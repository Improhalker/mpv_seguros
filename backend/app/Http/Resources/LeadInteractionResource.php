<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadInteractionResource extends JsonResource
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
            'leadId' => $this->lead_id,
            'userId' => $this->user_id,
            'type' => $this->type,
            'description' => $this->description,
            'occurredAt' => $this->occurred_at?->toISOString(),
            'nextContactAt' => $this->next_contact_at?->toISOString(),
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
