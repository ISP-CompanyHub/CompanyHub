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
        // vacation table
        Schema::create('vacation_request', function (Blueprint $table) {
            $table->id();
            $table->timestamp('submission_date');
            $table->timestamp('vacation_start');
            $table->timestamp('vacation_end');
            $table->string('type');
            $table->string('status');
            $table->longText('comments');
            $table->foreignId('user_id')->nullable(false)->constrained('users')->onDelete('cascade');
        });
        // holiday table
        Schema::create('holiday', function (Blueprint $table) {
            $table->id();
            $table->timestamp('holiday_date');
            $table->string('title');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacation_request');
        Schema::dropIfExists('holiday');

    }
};
