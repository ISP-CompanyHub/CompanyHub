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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('salary_components');
        Schema::enableForeignKeyConstraints();

        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_log_id')->constrained('salary_logs')->onDelete('cascade');
            $table->string('name');
            $table->decimal('sum', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->dropForeign(['salary_log_id']);
        });
        Schema::dropIfExists('salary_components');
    }
};
