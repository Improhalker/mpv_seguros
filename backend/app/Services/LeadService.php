<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeadService
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = $this->filteredQuery($filters);
        $sortBy = $filters['sort_by'] ?? null;
        $sortDirection = ($filters['sort_direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'status', 'insurance_type', 'urgency', 'qualification_score', 'next_contact_at', 'created_at'];

        if (is_string($sortBy) && in_array($sortBy, $allowedSorts, true)) {
            $query->orderBy($sortBy, $sortDirection)->orderByDesc('created_at');
        } else {
            $this->applyDefaultPriority($query);
        }

        $perPage = min(max((int) ($filters['per_page'] ?? 20), 1), 100);

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Lead
    {
        $lead = new Lead;
        $lead->fill($this->withDerivedAttributes($attributes));
        $lead->save();

        return $lead->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Lead $lead, array $attributes): Lead
    {
        $lead->fill($this->withDerivedAttributes($attributes, $lead));
        $lead->save();

        return $lead->fresh();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Lead>
     */
    private function filteredQuery(array $filters): Builder
    {
        $query = Lead::query()->with('assignedUser');
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $phoneSearch = preg_replace('/\D+/', '', $search) ?? '';

            $query->where(function (Builder $searchQuery) use ($operator, $search, $phoneSearch): void {
                $searchQuery
                    ->where('name', $operator, "%{$search}%")
                    ->orWhere('email', $operator, "%{$search}%");

                if ($phoneSearch !== '') {
                    $searchQuery->orWhere('phone', 'like', "%{$phoneSearch}%");
                }
            });
        }

        foreach (['status', 'insurance_type', 'urgency', 'source', 'assigned_user_id'] as $field) {
            if (($filters[$field] ?? null) !== null && ($filters[$field] ?? '') !== '') {
                $query->where($field, $filters[$field]);
            }
        }

        if (($filters['min_score'] ?? null) !== null && $filters['min_score'] !== '') {
            $query->where('qualification_score', '>=', (float) $filters['min_score']);
        }

        if (($filters['max_score'] ?? null) !== null && $filters['max_score'] !== '') {
            $query->where('qualification_score', '<=', (float) $filters['max_score']);
        }

        $this->applyContactPeriod($query, $filters['contact_period'] ?? null);

        return $query;
    }

    /** @param Builder<Lead> $query */
    private function applyContactPeriod(Builder $query, mixed $contactPeriod): void
    {
        if (! is_string($contactPeriod) || ! in_array($contactPeriod, ['overdue', 'today', 'upcoming'], true)) {
            return;
        }

        $now = now('America/Sao_Paulo');
        $startOfToday = $now->copy()->startOfDay()->utc();
        $endOfToday = $now->copy()->endOfDay()->utc();

        $query->whereNotIn('status', ['fechado', 'perdido']);

        if ($contactPeriod === 'overdue') {
            $query->where('next_contact_at', '<', $now->utc());
        }

        if ($contactPeriod === 'today') {
            $query->whereBetween('next_contact_at', [$startOfToday, $endOfToday]);
        }

        if ($contactPeriod === 'upcoming') {
            $query->where('next_contact_at', '>', $endOfToday);
        }
    }

    /** @param Builder<Lead> $query */
    private function applyDefaultPriority(Builder $query): void
    {
        $now = now('America/Sao_Paulo')->utc();
        $startOfToday = now('America/Sao_Paulo')->startOfDay()->utc();
        $endOfToday = now('America/Sao_Paulo')->endOfDay()->utc();

        $query
            ->orderByRaw(
                'case when next_contact_at is not null and next_contact_at < ? and status not in (?, ?) then 0 when next_contact_at between ? and ? and status not in (?, ?) then 1 else 2 end',
                [$now, 'fechado', 'perdido', $startOfToday, $endOfToday, 'fechado', 'perdido'],
            )
            ->orderByRaw("case urgency when 'alta' then 0 when 'media' then 1 when 'baixa' then 2 else 3 end")
            ->orderByDesc('qualification_score')
            ->orderByRaw("case when last_contact_at is null and status = 'novo' then 0 else 1 end")
            ->orderByDesc('created_at');
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function withDerivedAttributes(array $attributes, ?Lead $lead = null): array
    {
        unset($attributes['qualification_score']);

        $status = $attributes['status'] ?? $lead?->status ?? 'novo';
        $attributes['status'] = $status;

        if ($status === 'fechado') {
            if ($lead?->closed_at === null) {
                $attributes['closed_at'] = now();
            }
        } else {
            $attributes['closed_at'] = null;
        }

        if ($status === 'perdido') {
            if ($lead?->lost_at === null) {
                $attributes['lost_at'] = now();
            }
        } else {
            $attributes['lost_at'] = null;
            $attributes['loss_reason'] = null;
            $attributes['loss_reason_details'] = null;
        }

        $attributes['qualification_score'] = $this->qualificationScore($attributes, $lead);

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function qualificationScore(array $attributes, ?Lead $lead): ?float
    {
        $weights = [
            'financial_capacity_score' => 0.25,
            'sales_viability_score' => 0.30,
            'interest_level_score' => 0.30,
            'availability_score' => 0.15,
        ];
        $weightedTotal = 0.0;
        $totalWeight = 0.0;

        foreach ($weights as $field => $weight) {
            $value = array_key_exists($field, $attributes) ? $attributes[$field] : $lead?->getAttribute($field);

            if ($value !== null) {
                $weightedTotal += (float) $value * $weight;
                $totalWeight += $weight;
            }
        }

        if ($totalWeight === 0.0) {
            return null;
        }

        return round(($weightedTotal / $totalWeight) * 2, 1);
    }
}
