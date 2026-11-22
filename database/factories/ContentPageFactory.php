<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentPage>
 */
class ContentPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'path' => '/'.fake()->unique()->slug(),
            'eyebrow' => fake()->words(2, true),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(12),
            'details' => fake()->paragraph(),
            'items' => [],
            'sources' => [],
            'is_published' => true,
        ];
    }
}
