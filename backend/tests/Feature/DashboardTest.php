<?php

namespace Tests\Feature;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 7, 22, 13, 0, 0, 'America/Sao_Paulo'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_dashboard_returns_a_complete_empty_state(): void
    {
        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('summary.total_leads', 0)
            ->assertJsonPath('summary.contacts_today', 0)
            ->assertJsonCount(6, 'status_distribution')
            ->assertJsonCount(30, 'lead_evolution')
            ->assertJsonCount(0, 'insurance_distribution')
            ->assertJsonPath('priority_leads', [])
            ->assertJsonPath('upcoming_contacts', [])
            ->assertJsonPath('meta.period', 30)
            ->assertJsonPath('meta.date_from', '2026-06-23')
            ->assertJsonPath('meta.date_to', '2026-07-22');
    }

    public function test_dashboard_calculates_summary_metrics_and_excludes_ended_contacts(): void
    {
        Lead::factory()->create(['created_at' => $this->at('2026-07-22 09:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'status' => 'fechado', 'closed_at' => $this->at('2026-07-20 12:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'status' => 'perdido', 'lost_at' => $this->at('2026-07-21 12:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'next_contact_at' => $this->at('2026-07-22 15:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'next_contact_at' => $this->at('2026-07-21 15:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'status' => 'fechado', 'next_contact_at' => $this->at('2026-07-22 11:00:00')]);
        Lead::factory()->create(['created_at' => $this->at('2026-07-01 09:00:00'), 'status' => 'perdido', 'next_contact_at' => $this->at('2026-07-21 11:00:00')]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('summary.total_leads', 7)
            ->assertJsonPath('summary.created_today', 1)
            ->assertJsonPath('summary.closed_leads', 1)
            ->assertJsonPath('summary.lost_leads', 1)
            ->assertJsonPath('summary.contacts_today', 1)
            ->assertJsonPath('summary.overdue_contacts', 1);
    }

    public function test_dashboard_returns_real_distributions_evolution_and_filters(): void
    {
        Lead::factory()->create([
            'insurance_type' => 'auto',
            'status' => 'novo',
            'created_at' => $this->at('2026-07-20 10:00:00'),
        ]);
        Lead::factory()->create([
            'insurance_type' => 'vida',
            'status' => 'contatado',
            'created_at' => $this->at('2026-07-21 10:00:00'),
        ]);
        Lead::factory()->create([
            'insurance_type' => 'auto',
            'created_at' => $this->at('2026-07-01 10:00:00'),
        ]);

        $response = $this->getJson('/api/dashboard?period=7');

        $response->assertOk()
            ->assertJsonPath('summary.total_leads', 3)
            ->assertJsonPath('status_distribution.0.count', 1)
            ->assertJsonPath('status_distribution.1.count', 1)
            ->assertJsonFragment(['insurance_type' => 'auto', 'label' => 'Auto', 'count' => 1])
            ->assertJsonFragment(['date' => '2026-07-20', 'created' => 1, 'closed' => 0, 'lost' => 0])
            ->assertJsonFragment(['date' => '2026-07-21', 'created' => 1, 'closed' => 0, 'lost' => 0]);

        $this->getJson('/api/dashboard?period=7&insurance_type=auto')
            ->assertOk()
            ->assertJsonPath('summary.total_leads', 2)
            ->assertJsonPath('insurance_distribution.0.count', 1);
    }

    public function test_dashboard_prioritizes_overdue_then_today_and_orders_upcoming_contacts(): void
    {
        Lead::factory()->create(['name' => 'Sem prioridade', 'qualification_score' => 9.5]);
        Lead::factory()->create(['name' => 'Contato hoje', 'next_contact_at' => $this->at('2026-07-22 18:00:00')]);
        Lead::factory()->create(['name' => 'Contato atrasado', 'next_contact_at' => $this->at('2026-07-21 18:00:00')]);
        Lead::factory()->create(['name' => 'Próximo tarde', 'next_contact_at' => $this->at('2026-07-24 10:00:00')]);
        Lead::factory()->create(['name' => 'Próximo cedo', 'next_contact_at' => $this->at('2026-07-23 10:00:00'), 'qualification_score' => null]);
        Lead::factory()->create(['name' => 'Encerrado', 'status' => 'fechado', 'next_contact_at' => $this->at('2026-07-23 09:00:00')]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('priority_leads.0.name', 'Contato atrasado')
            ->assertJsonPath('priority_leads.1.name', 'Contato hoje')
            ->assertJsonPath('upcoming_contacts.0.name', 'Contato hoje')
            ->assertJsonPath('upcoming_contacts.1.name', 'Próximo cedo')
            ->assertJsonPath('upcoming_contacts.1.qualification_score', null)
            ->assertJsonPath('upcoming_contacts.2.name', 'Próximo tarde');
    }

    public function test_dashboard_preserves_ten_point_scores_and_validates_filters(): void
    {
        Lead::factory()->create([
            'name' => 'Lead qualificado',
            'qualification_score' => 8.8,
            'next_contact_at' => $this->at('2026-07-22 16:00:00'),
        ]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertJsonPath('priority_leads.0.qualification_score', 8.8);

        $this->getJson('/api/dashboard?period=10')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('period');

        $this->getJson('/api/dashboard?date_from=2026-07-20')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_to');

        $this->getJson('/api/dashboard?date_from=2026-07-22&date_to=2026-07-20')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_to');
    }

    private function at(string $dateTime): Carbon
    {
        return Carbon::parse($dateTime, 'America/Sao_Paulo')->utc();
    }
}
