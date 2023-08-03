<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(nbWords: 2),
            'image_url' => 'https://source.unsplash.com/random',
            'quantity' => random_int(20, 50),
            'price' => random_int(300, 500),
            'category_id' => random_int(1, 8),
        ];
    }
}
