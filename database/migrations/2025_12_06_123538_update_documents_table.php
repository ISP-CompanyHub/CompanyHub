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
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->constrained('users')->onDelete('cascade');
        });

        Schema::table('document_versions', function (Blueprint $table) {
            $table->integer('version_number');
            $table->timestamp('change_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('document_versions', function (Blueprint $table) {
            $table->dropColumn(['version_number', 'change_date']);
        });
    }
};
