<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Habit; // Import Habit
use App\Models\HabitCompletion; // Import HabitCompletion
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash
use Carbon\Carbon; // Import Carbon

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Create a Specific Test User ---
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Set a known password
        ]);

        // --- Create Habits for the Test User ---

        // 1. A daily habit with a decent streak
        $dailyHabit = Habit::factory()->recycle($testUser)->create([
            'title' => 'Exercise Daily',
            'frequency' => 'daily',
            'category' => 'Health',
        ]);
        // Create completions for the last 8 days for the daily habit
        for ($i = 0; $i < 8; $i++) {
            HabitCompletion::factory()->recycle($testUser)->recycle($dailyHabit)->create([
                'completion_date' => Carbon::today()->subDays($i)->toDateString(),
            ]);
        }

        // 2. A weekly habit completed for a few weeks
        $weeklyHabit = Habit::factory()->recycle($testUser)->create([
            'title' => 'Weekly Review',
            'frequency' => 'weekly',
            'category' => 'Productivity',
        ]);
        // Create completions in the last 3 weeks (one per week)
        for ($i = 0; $i < 3; $i++) {
             HabitCompletion::factory()->recycle($testUser)->recycle($weeklyHabit)->create([
                'completion_date' => Carbon::today()->subWeeks($i)->toDateString(),
             ]);
        }

        // 3. A daily habit with a broken streak
        $brokenStreakHabit = Habit::factory()->recycle($testUser)->create([
            'title' => 'Read for 15 Minutes',
            'frequency' => 'daily',
            'category' => 'Learning',
        ]);
        // Completed 2, 3, and 5 days ago (missed yesterday and 4 days ago)
        HabitCompletion::factory()->recycle($testUser)->recycle($brokenStreakHabit)->create(['completion_date' => Carbon::today()->subDays(2)->toDateString()]);
        HabitCompletion::factory()->recycle($testUser)->recycle($brokenStreakHabit)->create(['completion_date' => Carbon::today()->subDays(3)->toDateString()]);
        HabitCompletion::factory()->recycle($testUser)->recycle($brokenStreakHabit)->create(['completion_date' => Carbon::today()->subDays(5)->toDateString()]);


        // 4. Another habit (no completions yet)
        Habit::factory()->recycle($testUser)->create([
            'title' => 'Drink 8 Glasses of Water',
            'frequency' => 'daily',
            'category' => 'Health',
        ]);

        // 5. More habits (optional)
        Habit::factory()->count(3)->recycle($testUser)->create(); // Create 3 more random habits


        // --- IMPORTANT: Recalculate Streaks ---
        // After creating completions, recalculate streaks for all seeded habits
        $this->command->info('Recalculating streaks for seeded habits...');
        $allHabits = Habit::all(); // Get all habits (including any potentially existing ones)
        foreach ($allHabits as $habit) {
            $habit->updateStreak(); // Use the method we created earlier
        }
        $this->command->info('Streak recalculation complete.');
    }
}
