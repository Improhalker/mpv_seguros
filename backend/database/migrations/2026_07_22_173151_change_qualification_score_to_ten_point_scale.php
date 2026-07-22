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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('alter table leads alter column qualification_score type numeric(3, 1) using round(qualification_score, 1)');

            return;
        }

        Schema::table('leads', function (Blueprint $table): void {
            $table->decimal('qualification_score', 3, 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('alter table leads alter column qualification_score type numeric(5, 2)');

            return;
        }

        Schema::table('leads', function (Blueprint $table): void {
            $table->decimal('qualification_score', 5, 2)->nullable()->change();
        });
    }
};
