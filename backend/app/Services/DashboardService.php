<?php

namespace App\Services;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private const Timezone = 'America/Sao_Paulo';

    private const ClosedStatuses = ['fechado', 'perdido'];

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function get(array $filters, ?int $organizationId = null): array
    {
        [$dateFrom, $dateTo, $period] = $this->dateRange($filters);
        $now = now(self::Timezone);
        $todayStart = $now->copy()->startOfDay()->utc();
        $todayEnd = $now->copy()->endOfDay()->utc();
        $baseQuery = $this->baseQuery($filters, $organizationId);

        return [
            'summary' => $this->summary($baseQuery, $todayStart, $todayEnd, $dateFrom, $dateTo, $now),
            'status_distribution' => $this->statusDistribution($baseQuery, $dateFrom, $dateTo),
            'lead_evolution' => $this->leadEvolution($baseQuery, $dateFrom, $dateTo),
            'insurance_distribution' => $this->insuranceDistribution($baseQuery, $dateFrom, $dateTo),
            'priority_leads' => $this->priorityLeads($baseQuery, $todayStart, $todayEnd, $now),
            'upcoming_contacts' => $this->upcomingContacts($baseQuery, $now),
            'meta' => [
                'period' => $period,
                'date_from' => $dateFrom->setTimezone(self::Timezone)->toDateString(),
                'date_to' => $dateTo->setTimezone(self::Timezone)->toDateString(),
                'generated_at' => now()->utc()->toISOString(),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{0: Carbon, 1: Carbon, 2: int|null}
     */
    private function dateRange(array $filters): array
    {
        if (isset($filters['date_from'], $filters['date_to'])) {
            return [
                Carbon::parse($filters['date_from'], self::Timezone)->startOfDay()->utc(),
                Carbon::parse($filters['date_to'], self::Timezone)->endOfDay()->utc(),
                null,
            ];
        }

        $period = (int) ($filters['period'] ?? 30);
        $today = now(self::Timezone);

        return [
            $today->copy()->subDays($period - 1)->startOfDay()->utc(),
            $today->copy()->endOfDay()->utc(),
            $period,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Lead>
     */
    private function baseQuery(array $filters, ?int $organizationId): Builder
    {
        $query = Lead::query();

        if ($organizationId !== null) {
            $query->where('organization_id', $organizationId);
        }

        foreach (['insurance_type', 'assigned_user_id'] as $filter) {
            if (($filters[$filter] ?? null) !== null && $filters[$filter] !== '') {
                $query->where($filter, $filters[$filter]);
            }
        }

        return $query;
    }

    /** @return array<string, int> */
    private function summary(Builder $baseQuery, Carbon $todayStart, Carbon $todayEnd, Carbon $dateFrom, Carbon $dateTo, Carbon $now): array
    {
        $row = (clone $baseQuery)->selectRaw(
            'count(*) as total_leads,
            sum(case when created_at between ? and ? then 1 else 0 end) as created_today,
            sum(case when status = ? and closed_at between ? and ? then 1 else 0 end) as closed_leads,
            sum(case when status = ? and lost_at between ? and ? then 1 else 0 end) as lost_leads,
            sum(case when next_contact_at between ? and ? and status not in (?, ?) then 1 else 0 end) as contacts_today,
            sum(case when next_contact_at < ? and status not in (?, ?) then 1 else 0 end) as overdue_contacts',
            [
                $todayStart, $todayEnd,
                'fechado', $dateFrom, $dateTo,
                'perdido', $dateFrom, $dateTo,
                $todayStart, $todayEnd, ...self::ClosedStatuses,
                $now->utc(), ...self::ClosedStatuses,
            ],
        )->first();

        return [
            'total_leads' => (int) ($row->total_leads ?? 0),
            'created_today' => (int) ($row->created_today ?? 0),
            'closed_leads' => (int) ($row->closed_leads ?? 0),
            'lost_leads' => (int) ($row->lost_leads ?? 0),
            'contacts_today' => (int) ($row->contacts_today ?? 0),
            'overdue_contacts' => (int) ($row->overdue_contacts ?? 0),
        ];
    }

    /** @return array<int, array{status: string, label: string, count: int}> */
    private function statusDistribution(Builder $baseQuery, Carbon $dateFrom, Carbon $dateTo): array
    {
        $counts = (clone $baseQuery)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return collect($this->statusLabels())->map(fn (string $label, string $status): array => [
            'status' => $status,
            'label' => $label,
            'count' => (int) ($counts[$status] ?? 0),
        ])->values()->all();
    }

    /** @return array<int, array{date: string, created: int, closed: int, lost: int}> */
    private function leadEvolution(Builder $baseQuery, Carbon $dateFrom, Carbon $dateTo): array
    {
        $created = $this->countByDate((clone $baseQuery)->whereBetween('created_at', [$dateFrom, $dateTo]), 'created_at');
        $closed = $this->countByDate((clone $baseQuery)->where('status', 'fechado')->whereBetween('closed_at', [$dateFrom, $dateTo]), 'closed_at');
        $lost = $this->countByDate((clone $baseQuery)->where('status', 'perdido')->whereBetween('lost_at', [$dateFrom, $dateTo]), 'lost_at');
        $date = $dateFrom->copy()->setTimezone(self::Timezone)->startOfDay();
        $lastDate = $dateTo->copy()->setTimezone(self::Timezone)->startOfDay();
        $evolution = [];

        while ($date->lte($lastDate)) {
            $key = $date->toDateString();
            $evolution[] = [
                'date' => $key,
                'created' => $created[$key] ?? 0,
                'closed' => $closed[$key] ?? 0,
                'lost' => $lost[$key] ?? 0,
            ];
            $date->addDay();
        }

        return $evolution;
    }

    /** @return array<string, int> */
    private function countByDate(Builder $query, string $column): array
    {
        $dateExpression = DB::connection()->getDriverName() === 'pgsql'
            ? "to_char({$column} at time zone 'America/Sao_Paulo', 'YYYY-MM-DD')"
            : "strftime('%Y-%m-%d', {$column})";

        return $query->selectRaw("{$dateExpression} as date, count(*) as aggregate")
            ->groupBy('date')
            ->pluck('aggregate', 'date')
            ->map(fn (mixed $count): int => (int) $count)
            ->all();
    }

    /** @return array<int, array{insurance_type: string, label: string, count: int}> */
    private function insuranceDistribution(Builder $baseQuery, Carbon $dateFrom, Carbon $dateTo): array
    {
        return (clone $baseQuery)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('insurance_type, count(*) as aggregate')
            ->groupBy('insurance_type')
            ->orderByDesc('aggregate')
            ->get()
            ->map(fn (object $row): array => [
                'insurance_type' => $row->insurance_type,
                'label' => $this->insuranceLabel($row->insurance_type),
                'count' => (int) $row->aggregate,
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    private function priorityLeads(Builder $baseQuery, Carbon $todayStart, Carbon $todayEnd, Carbon $now): array
    {
        $leads = (clone $baseQuery)
            ->with('assignedUser:id,name')
            ->whereNotIn('status', self::ClosedStatuses)
            ->select(['id', 'assigned_user_id', 'name', 'phone', 'insurance_type', 'status', 'urgency', 'qualification_score', 'last_contact_at', 'next_contact_at', 'created_at'])
            ->orderByRaw(
                'case when next_contact_at is not null and next_contact_at < ? then 0 when next_contact_at between ? and ? then 1 else 2 end',
                [$now->utc(), $todayStart, $todayEnd],
            )
            ->orderByRaw("case urgency when 'alta' then 0 else 1 end")
            ->orderByDesc('qualification_score')
            ->orderByRaw("case when last_contact_at is null and status = 'novo' then 0 else 1 end")
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return $this->leadList($leads);
    }

    /** @return array<int, array<string, mixed>> */
    private function upcomingContacts(Builder $baseQuery, Carbon $now): array
    {
        $leads = (clone $baseQuery)
            ->with('assignedUser:id,name')
            ->whereNotIn('status', self::ClosedStatuses)
            ->where('next_contact_at', '>', $now->utc())
            ->select(['id', 'assigned_user_id', 'name', 'phone', 'insurance_type', 'status', 'urgency', 'qualification_score', 'last_contact_at', 'next_contact_at', 'created_at'])
            ->orderBy('next_contact_at')
            ->limit(8)
            ->get();

        return $this->leadList($leads);
    }

    /**
     * @param  iterable<Lead>  $leads
     * @return array<int, array<string, mixed>>
     */
    private function leadList(iterable $leads): array
    {
        $list = [];

        foreach ($leads as $lead) {
            $list[] = [
                'id' => $lead->id,
                'name' => $lead->name,
                'phone' => $lead->phone,
                'insurance_type' => $lead->insurance_type,
                'status' => $lead->status,
                'urgency' => $lead->urgency,
                'qualification_score' => $lead->qualification_score === null ? null : (float) $lead->qualification_score,
                'last_contact_at' => $lead->last_contact_at?->toISOString(),
                'next_contact_at' => $lead->next_contact_at?->toISOString(),
                'created_at' => $lead->created_at?->toISOString(),
                'assigned_user' => $lead->assignedUser ? [
                    'id' => $lead->assignedUser->id,
                    'name' => $lead->assignedUser->name,
                ] : null,
            ];
        }

        return $list;
    }

    /** @return array<string, string> */
    private function statusLabels(): array
    {
        return [
            'novo' => 'Novo',
            'contatado' => 'Contatado',
            'em_negociacao' => 'Em negociação',
            'aguardando_cliente' => 'Aguardando cliente',
            'fechado' => 'Fechado',
            'perdido' => 'Perdido',
        ];
    }

    private function insuranceLabel(string $insuranceType): string
    {
        return [
            'auto' => 'Auto',
            'residencial' => 'Residencial',
            'vida' => 'Vida',
            'empresarial' => 'Empresarial',
            'saude' => 'Saúde',
            'viagem' => 'Viagem',
            'previdencia' => 'Previdência',
            'outro' => 'Outro',
        ][$insuranceType] ?? ucfirst($insuranceType);
    }
}
