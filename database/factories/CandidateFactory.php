<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_posting_id' => \App\Models\JobPosting::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'resume_url' => 'resumes/'.fake()->uuid().'.pdf',
            'status' => fake()->randomElement(['new', 'reviewing', 'interview_scheduled', 'interviewed']),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the candidate is new.
     */
    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
        ]);
    }

    /**
     * Indicate that the candidate is being reviewed.
     */
    public function reviewing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reviewing',
        ]);
    }

    /**
     * Indicate that the candidate has an interview scheduled.
     */
    public function interviewScheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'interview_scheduled',
        ]);
    }

    /**
     * Indicate that the candidate has been interviewed.
     */
    public function interviewed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'interviewed',
        ]);
    }
}
