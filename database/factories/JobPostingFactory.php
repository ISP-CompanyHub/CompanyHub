<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPosting>
 */
class JobPostingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->city(),
            'employment_type' => fake()->randomElement(['Full-time', 'Part-time', 'Contract', 'Internship']),
            'salary_min' => $salaryMin = fake()->numberBetween(30000, 70000),
            'salary_max' => fake()->numberBetween($salaryMin, $salaryMin + 50000),
            'is_active' => fake()->boolean(80), // 80% active
            'posted_at' => now(),
        ];
    }

    /**
     * Indicate that the job posting is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the job posting is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
