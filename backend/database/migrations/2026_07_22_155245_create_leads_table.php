<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('insurance_type');
            $table->string('custom_insurance_type')->nullable();
            $table->string('status')->default('novo');
            $table->string('urgency')->nullable();
            $table->string('source')->nullable();
            $table->string('source_details')->nullable();
            $table->text('general_notes')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('occupation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('income_range')->nullable();
            $table->boolean('has_current_insurance')->nullable();
            $table->string('current_insurer')->nullable();
            $table->date('current_policy_expires_at')->nullable();
            $table->decimal('estimated_asset_value', 15, 2)->nullable();
            $table->decimal('available_budget', 15, 2)->nullable();
            $table->text('needs_description')->nullable();
            $table->string('preferred_contact_period')->nullable();
            $table->unsignedTinyInteger('financial_capacity_score')->nullable();
            $table->unsignedTinyInteger('sales_viability_score')->nullable();
            $table->unsignedTinyInteger('interest_level_score')->nullable();
            $table->unsignedTinyInteger('availability_score')->nullable();
            $table->decimal('qualification_score', 3, 1)->nullable();
            $table->timestampTz('last_contact_at')->nullable();
            $table->timestampTz('next_contact_at')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampTz('lost_at')->nullable();
            $table->string('loss_reason')->nullable();
            $table->text('loss_reason_details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('assigned_user_id');
            $table->index('created_by');
            $table->index('insurance_type');
            $table->index('urgency');
            $table->index('qualification_score');
            $table->index('next_contact_at');
            $table->index('created_at');
            $table->index(['status', 'next_contact_at']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('alter table leads enable row level security');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
