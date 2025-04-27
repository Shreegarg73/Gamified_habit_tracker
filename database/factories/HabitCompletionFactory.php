<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // Import User
use App\Models\Habit; // Import Habit

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HabitCompletion>
 */
class HabitCompletionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Assume user_id and habit_id will be set when calling the factory
            'user_id' => User::factory(), // Default, but usually overridden
            'habit_id' => Habit::factory(), // Default, but usually overridden
            'completion_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Random date in the past year
        ];
    }
}
