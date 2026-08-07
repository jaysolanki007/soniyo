<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->decimal('base_salary', 12, 2)->default(0)->after('commission_percent');
            // commission_percent (existing) = service / flat rate
            $table->decimal('product_commission_percent', 5, 2)->default(0)->after('base_salary');
            $table->string('commission_type')->default('flat')->after('product_commission_percent'); // flat | split
            $table->decimal('target_amount', 12, 2)->default(0)->after('commission_type');   // monthly sales target
            $table->decimal('target_bonus', 12, 2)->default(0)->after('target_amount');       // bonus paid when target met
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['base_salary', 'product_commission_percent', 'commission_type', 'target_amount', 'target_bonus']);
        });
    }
};
