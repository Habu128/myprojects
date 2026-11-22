<?php

namespace Database\Factories;

use App\Models\CouncilProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CouncilProject>
 */
class CouncilProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'ward' => fake()->randomElement(['Bargu / Burashika', 'Buma', 'Gasi / Salifawa', 'Gora', 'Gwalasho', 'Gwaskara', 'Kombo', 'Kubo', 'Kwaba', 'Shani', 'Walama']),
            'description' => fake()->paragraph(),
            'budget_amount' => fake()->randomFloat(2, 100000, 50000000),
            'budget_year' => fake()->numberBetween(2024, 2026),
            'status' => fake()->randomElement(['planned', 'in_progress', 'completed', 'paused']),
            'source_title' => 'Example source record',
            'source_url' => 'https://example.com/source-record',
            'evidence_url' => null,
            'updated_by' => null,
        ];
    }
}
