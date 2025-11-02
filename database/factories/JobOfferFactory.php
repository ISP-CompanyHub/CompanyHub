<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = now()->year;
        $count = \App\Models\JobOffer::whereYear('created_at', $year)->count() + 1;

        return [
            'candidate_id' => \App\Models\Candidate::factory(),
            'job_posting_id' => fn (array $attributes) => \App\Models\Candidate::find($attributes['candidate_id'])->job_posting_id,
            'offer_number' => "JO-{$year}-".str_pad($count, 3, '0', STR_PAD_LEFT),
            'salary' => fake()->numberBetween(40000, 120000),
            'start_date' => fake()->dateTimeBetween('now', '+60 days'),
            'expires_at' => fake()->dateTimeBetween('+60 days', '+90 days'),
            'status' => 'draft',
            'pdf_path' => null,
            'sent_at' => null,
        ];
    }

    /**
     * Indicate a draft job offer.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'sent_at' => null,
            'pdf_path' => null,
        ]);
    }

    /**
     * Indicate a sent job offer.
     */
    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => now(),
            'pdf_path' => 'job-offers/'.fake()->uuid().'.pdf',
        ]);
    }
}
