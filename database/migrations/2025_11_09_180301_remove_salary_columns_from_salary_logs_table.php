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
            $table->dropColumn(['gross_salary', 'net_salary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_logs', function (Blueprint $table) {
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
        });
    }
};
