<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 3,
            'maize_id' => random_int(1, 8),
            'quantity' => random_int(1, 3),
            'unit_price' => random_int(300, 500),
            'recipe_id' => random_int(1, 200),
            'is_completed' => random_int(0, 1),
        ];
    }
}
