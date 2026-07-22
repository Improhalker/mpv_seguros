<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadInteraction;
use Illuminate\Support\Facades\DB;

class LeadInteractionService
{
    /** @param array<string, mixed> $attributes */
    public function create(Lead $lead, array $attributes): LeadInteraction
    {
        return DB::transaction(function () use ($lead, $attributes): LeadInteraction {
            $interaction = $lead->interactions()->create($attributes);
            $this->syncLeadContactDates($lead, $attributes);

            return $interaction->fresh();
        });
    }

    /** @param array<string, mixed> $attributes */
    public function update(Lead $lead, LeadInteraction $interaction, array $attributes): LeadInteraction
    {
        return DB::transaction(function () use ($lead, $interaction, $attributes): LeadInteraction {
            $interaction->fill($attributes);
            $interaction->save();
            $this->syncLeadContactDates($lead, $attributes);

            return $interaction->fresh();
        });
    }

    public function delete(Lead $lead, LeadInteraction $interaction): void
    {
        DB::transaction(function () use ($lead, $interaction): void {
            $interaction->delete();
            $this->syncLeadContactDates($lead, []);
        });
    }

    /** @param array<string, mixed> $attributes */
    private function syncLeadContactDates(Lead $lead, array $attributes): void
    {
        $updates = [
            'last_contact_at' => $lead->interactions()->max('occurred_at'),
        ];

        if (array_key_exists('next_contact_at', $attributes)) {
            $updates['next_contact_at'] = $attributes['next_contact_at'];
        }

        $lead->forceFill($updates)->save();
    }
}
