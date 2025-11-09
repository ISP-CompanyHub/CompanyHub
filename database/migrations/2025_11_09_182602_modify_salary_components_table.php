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
        Schema::table('salary_components', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_percentage', 'applies_to_all']);
            $table->renameColumn('value', 'default_amount');
            $table->decimal('default_amount', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_components', function (Blueprint $table) {
            $table->enum('type', ['earning', 'deduction']);
            $table->boolean('is_percentage')->default(false);
            $table->boolean('applies_to_all')->default(false);
            $table->renameColumn('default_amount', 'value');
            $table->decimal('value', 8, 2)->nullable(false)->change();
        });
    }
};
