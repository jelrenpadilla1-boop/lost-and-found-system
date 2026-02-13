<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LostItemFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Electronics', 'Documents', 'Jewelry', 'Clothing', 'Bags', 'Keys', 'Wallet', 'Other'];
        
        return [
            'user_id' => User::factory(),
            'item_name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement($categories),
            'photo' => null,
            'date_lost' => fake()->dateTimeBetween('-30 days', 'now'),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'status' => fake()->randomElement(['pending', 'found', 'returned']),
        ];
    }
}