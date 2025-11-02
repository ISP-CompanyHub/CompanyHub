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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->nullable(false)->constrained('job_postings')->onDelete('cascade');
            $table->string('name')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('phone')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('status')->nullable(false)->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
