<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            update leads
            set qualification_score = case
                when financial_capacity_score is null
                    and sales_viability_score is null
                    and interest_level_score is null
                    and availability_score is null
                    then null
                else round(
                    (
                        coalesce(financial_capacity_score * 0.25, 0)
                        + coalesce(sales_viability_score * 0.30, 0)
                        + coalesce(interest_level_score * 0.30, 0)
                        + coalesce(availability_score * 0.15, 0)
                    )
                    /
                    (
                        case when financial_capacity_score is not null then 0.25 else 0 end
                        + case when sales_viability_score is not null then 0.30 else 0 end
                        + case when interest_level_score is not null then 0.30 else 0 end
                        + case when availability_score is not null then 0.15 else 0 end
                    )
                    * 2,
                    1
                )
            end
        SQL);
    }

    public function down(): void
    {
        // The previous values cannot be restored reliably.
    }
};
