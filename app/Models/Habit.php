<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany
use Carbon\Carbon; // Import Carbon for date manipulation
use Carbon\CarbonPeriod; // Import CarbonPeriod for date range iteration

class Habit extends Model
{
    use HasFactory; // Provides factory helper methods for testing/seeding

    /**
     * The attributes that are mass assignable.
     *
     * Mass assignment protection prevents accidentally updating columns you didn't intend to.
     * Only attributes listed here can be filled using methods like `create()` or `update()`.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Make sure user_id is fillable if you set it directly
        'title',
        'description',
        'category',
        'frequency',
        'current_streak', // Include streak fields if they can be updated directly
        'longest_streak',
    ];

    /**
     * Define the inverse of the one-to-many relationship.
     * Get the user that owns the habit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     /**
      * Get all of the completions for the Habit.
      * Defines the one-to-many relationship with HabitCompletion.
      */
     public function completions(): HasMany
     {
         return $this->hasMany(HabitCompletion::class)->orderBy('completion_date', 'desc'); // Order by date descending
     }

    /**
     * Check if the habit has been completed today (for daily habits).
     */
    public function isCompletedToday(): bool
    {
        if ($this->frequency !== 'daily') {
            return false; // Or handle other frequencies differently if needed
        }
        // Check if a completion record exists for this habit with today's date
        return $this->completions()
                    ->where('completion_date', Carbon::today()->toDateString())
                    ->exists();
    }

     /**
      * Check if the habit has been completed this week (for weekly habits).
      * (Assumes week starts on Monday)
      */
     public function isCompletedThisWeek(): bool
     {
         if ($this->frequency !== 'weekly') {
             return false;
         }
         $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
         $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateString();

         return $this->completions()
                     ->whereBetween('completion_date', [$startOfWeek, $endOfWeek])
                     ->exists();
     }

     /**
      * Recalculate and update the current and longest streaks for the habit.
      * This should be called after a new completion is successfully recorded OR potentially
      * periodically to reset streaks if a period was missed (more complex).
      */
     public function updateStreak(): void
     {
         $completions = $this->completions()
                             ->orderBy('completion_date', 'desc')
                             ->pluck('completion_date'); // Get collection of Carbon date objects

         if ($completions->isEmpty()) {
             if ($this->current_streak != 0) { // Only save if change needed
                $this->current_streak = 0;
                $this->save();
             }
             return;
         }

         $currentStreak = 0;
         $today = Carbon::today();
         $mostRecentCompletion = $completions->first(); // Already ordered desc

         if ($this->frequency === 'daily') {
             // Check if the most recent completion is actually today
             if (!$mostRecentCompletion->isSameDay($today)) {
                 // If the latest completion isn't today, the streak is broken
                 if ($this->current_streak != 0) {
                     $this->current_streak = 0;
                     $this->save();
                 }
                 return; // Exit because streak is 0
             }

             // Start checking from yesterday backwards
             $currentStreak = 1; // Today counts as 1
             $checkDate = $today->copy()->subDay();

             foreach ($completions->slice(1) as $completionDate) { // Skip today's completion
                 if ($completionDate->isSameDay($checkDate)) {
                     $currentStreak++;
                     $checkDate->subDay(); // Move to the previous day to check
                 } else if ($completionDate->lessThan($checkDate)) {
                     // If the completion date is *before* the date we are checking,
                     // it means there's a gap. Streak broken earlier.
                     break;
                 }
                 // If completion date is after checkDate (e.g., multiple completions on one day before today), ignore it and continue checking the required date.
             }

         } elseif ($this->frequency === 'weekly') {
             $startOfThisWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
             $endOfThisWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

             // Check if the most recent completion is actually this week
             if (!$mostRecentCompletion->betweenIncluded($startOfThisWeek, $endOfThisWeek)) {
                 // If the latest completion isn't this week, the streak is broken
                 if ($this->current_streak != 0) {
                    $this->current_streak = 0;
                    $this->save();
                 }
                 return; // Exit because streak is 0
             }

             $currentStreak = 1; // This week counts as 1
             // Start checking from the beginning of last week
             $startOfCheckWeek = $startOfThisWeek->copy()->subWeek();
             $endOfCheckWeek = $startOfCheckWeek->copy()->endOfWeek(Carbon::SUNDAY);

             // Use a set of unique completion dates for efficient checking
             $uniqueCompletionDates = $completions->map(fn($date) => $date->toDateString())->unique()->values();

             while (true) {
                 $foundCompletionInWeek = false;
                 // Check if any completion date falls within the check week
                 foreach ($uniqueCompletionDates as $dateString) {
                     // Optimization: if the date string is older than the end of the check week, check it
                     if ($dateString <= $endOfCheckWeek->toDateString()) {
                          // Check if it's also within the start of the week
                          if ($dateString >= $startOfCheckWeek->toDateString()){
                            $foundCompletionInWeek = true;
                            break; // Found one for this week, move to the previous week
                          }
                     }
                 }

                 if ($foundCompletionInWeek) {
                     $currentStreak++;
                     // Move to the previous week
                     $startOfCheckWeek->subWeek();
                     $endOfCheckWeek->subWeek();
                 } else {
                     // No completion found for the check week, streak ends here
                     break;
                 }

                 // Safety break: Stop if checking very old dates (e.g., > 3 years to be safe)
                 if ($startOfCheckWeek->diffInYears($today) > 3) break;
             }
         }

         // Update current streak only if it has changed
         if ($this->current_streak !== $currentStreak) {
            $this->current_streak = $currentStreak;
         }

         // Update longest streak if current is greater
         if ($this->current_streak > $this->longest_streak) {
             $this->longest_streak = $this->current_streak;
         }

         // Save only if any streak value has actually changed
         if ($this->isDirty('current_streak') || $this->isDirty('longest_streak')) {
             $this->save();
         }
     }

    /**
     * Define possible categories (could also be in config or separate table)
     */
    public static function getCategories(): array
    {
        return ['Health', 'Productivity', 'Learning', 'Finance', 'Personal', 'Other'];
    }

    /**
     * Define possible frequencies
     */
    public static function getFrequencies(): array
    {
        return ['daily', 'weekly'];
    }
}
