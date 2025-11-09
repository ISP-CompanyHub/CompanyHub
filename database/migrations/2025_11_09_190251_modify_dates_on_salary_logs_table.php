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
        Schema::table('salary_logs', function (Blueprint $table) {
            $table->dropColumn('calculation_date');
            $table->date('period_from')->nullable();
            $table->date('period_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_logs', function (Blueprint $table) {
            $table->date('calculation_date');
            $table->dropColumn(['period_from', 'period_until']);
        });
    }
};
