<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'organization_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'organization_id')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
