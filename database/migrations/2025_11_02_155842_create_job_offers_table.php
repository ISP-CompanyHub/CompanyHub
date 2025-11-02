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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->nullable(false)->constrained('candidates')->onDelete('cascade');
            $table->foreignId('job_posting_id')->nullable(false)->constrained('job_postings')->onDelete('cascade');
            $table->string('offer_number')->nullable(false);
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('status')->nullable(false)->default('draft');
            $table->string('pdf_path')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
