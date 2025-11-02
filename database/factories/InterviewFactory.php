<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'candidate_id' => \App\Models\Candidate::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+30 days'),
            'location' => fake()->randomElement([
                fake()->address(),
                'Virtual Meeting',
                null,
            ]),
            'mode' => fake()->randomElement(['in-person', 'video', 'phone']),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate an upcoming interview.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_at' => fake()->dateTimeBetween('now', '+30 days'),
        ]);
    }

    /**
     * Indicate a past interview.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate an in-person interview.
     */
    public function inPerson(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode' => 'in-person',
            'location' => fake()->address(),
        ]);
    }

    /**
     * Indicate a video interview.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode' => 'video',
            'location' => 'Virtual Meeting - Link will be sent',
        ]);
    }

    /**
     * Indicate a phone interview.
     */
    public function phone(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode' => 'phone',
            'location' => null,
        ]);
    }
}
