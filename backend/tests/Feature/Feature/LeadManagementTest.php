<?php

namespace Tests\Feature\Feature;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_leads_are_paginated(): void
    {
        Lead::factory()->count(3)->create();

        $this->getJson('/api/leads?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 3);
    }

    public function test_leads_can_be_searched_by_name_phone_and_email(): void
    {
        Lead::factory()->create([
            'name' => 'Maria da Silva',
            'phone' => '11987654321',
            'email' => 'maria@exemplo.com',
        ]);

        foreach (['Maria', '987654321', 'maria@exemplo.com'] as $term) {
            $this->getJson('/api/leads?search='.urlencode($term))
                ->assertOk()
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Maria da Silva');
        }
    }

    public function test_lead_can_be_created_with_only_required_fields(): void
    {
        $this->postJson('/api/leads', [
            'name' => 'Ana Corretora',
            'phone' => '(11) 98765-4321',
            'insurance_type' => 'auto',
        ])
            ->assertCreated()
            ->assertJsonPath('status', 'novo')
            ->assertJsonPath('phone', '11987654321');

        $this->assertDatabaseHas('leads', [
            'name' => 'Ana Corretora',
            'phone' => '11987654321',
            'insurance_type' => 'auto',
        ]);
    }

    public function test_required_lead_fields_are_validated(): void
    {
        $this->postJson('/api/leads', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone', 'insurance_type']);
    }

    public function test_lead_can_be_updated_without_clearing_omitted_fields(): void
    {
        $lead = Lead::factory()->create(['email' => 'original@exemplo.com']);

        $this->putJson("/api/leads/{$lead->id}", ['name' => 'Novo nome'])
            ->assertOk()
            ->assertJsonPath('name', 'Novo nome')
            ->assertJsonPath('email', 'original@exemplo.com');
    }

    public function test_lead_is_soft_deleted(): void
    {
        $lead = Lead::factory()->create();

        $this->deleteJson("/api/leads/{$lead->id}")->assertNoContent();

        $this->assertSoftDeleted('leads', ['id' => $lead->id]);
        $this->getJson("/api/leads/{$lead->id}")->assertNotFound();
    }

    public function test_qualification_score_is_calculated_by_the_backend(): void
    {
        $this->postJson('/api/leads', [
            'name' => 'Lead qualificado',
            'phone' => '11987654321',
            'insurance_type' => 'vida',
            'financial_capacity_score' => 5,
            'sales_viability_score' => 4,
            'interest_level_score' => 5,
            'availability_score' => 3,
            'qualification_score' => 10,
        ])->assertCreated()->assertJsonPath('qualificationScore', 8.8);
    }

    public function test_a_score_of_seven_is_preserved_on_the_ten_point_scale(): void
    {
        $this->postJson('/api/leads', [
            'name' => 'Lead com nota sete',
            'phone' => '11987654321',
            'insurance_type' => 'vida',
            'financial_capacity_score' => 5,
            'sales_viability_score' => 3,
            'interest_level_score' => 4,
            'availability_score' => 1,
        ])->assertCreated()->assertJsonPath('qualificationScore', 7);
    }

    public function test_partial_scores_are_reweighted(): void
    {
        $this->postJson('/api/leads', [
            'name' => 'Lead parcial',
            'phone' => '11987654321',
            'insurance_type' => 'vida',
            'financial_capacity_score' => 4,
            'interest_level_score' => 2,
        ])->assertCreated()->assertJsonPath('qualificationScore', 5.8);
    }

    public function test_lost_lead_requires_a_loss_reason(): void
    {
        $this->postJson('/api/leads', [
            'name' => 'Lead perdido',
            'phone' => '11987654321',
            'insurance_type' => 'auto',
            'status' => 'perdido',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('loss_reason');
    }

    public function test_interaction_updates_last_and_next_contact_dates(): void
    {
        $lead = Lead::factory()->create();
        $occurredAt = now()->subHour()->toISOString();
        $nextContactAt = now()->addDay()->toISOString();

        $this->postJson("/api/leads/{$lead->id}/interactions", [
            'type' => 'ligacao',
            'description' => 'Ligação realizada.',
            'occurred_at' => $occurredAt,
            'next_contact_at' => $nextContactAt,
        ])->assertCreated();

        $lead->refresh();

        $this->assertSame(Carbon::parse($occurredAt)->startOfSecond()->toISOString(), $lead->last_contact_at?->toISOString());
        $this->assertSame(Carbon::parse($nextContactAt)->startOfSecond()->toISOString(), $lead->next_contact_at?->toISOString());
    }

    public function test_filters_and_default_priority_are_applied_by_the_backend(): void
    {
        $overdue = Lead::factory()->create([
            'status' => 'novo',
            'urgency' => 'baixa',
            'next_contact_at' => now()->subDay(),
        ]);
        Lead::factory()->create([
            'status' => 'novo',
            'urgency' => 'alta',
            'next_contact_at' => now()->addDay(),
        ]);
        Lead::factory()->create(['status' => 'fechado']);

        $this->getJson('/api/leads?status=novo&urgency=alta')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.urgency', 'alta');

        $this->getJson('/api/leads?min_score=10.1')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('min_score');

        $this->getJson('/api/leads')
            ->assertOk()
            ->assertJsonPath('data.0.id', $overdue->id);
    }
}
