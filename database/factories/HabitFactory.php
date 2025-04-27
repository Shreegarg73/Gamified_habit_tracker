<?php

namespace Database\Factories;

use App\Models\Habit;
use App\Models\User; // Import User model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habit>
 */
class HabitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Associate with a User (assumes User::factory() exists or is provided)
            'user_id' => User::factory(),
            'title' => fake()->unique()->sentence(3), // Generate a short, unique sentence
            'description' => fake()->paragraph(1), // Optional description
            'category' => fake()->randomElement(Habit::getCategories()), // Pick a random category
            'frequency' => fake()->randomElement(Habit::getFrequencies()), // Pick random frequency
            'current_streak' => 0, // Default streaks to 0
            'longest_streak' => 0,
        ];
    }
}
