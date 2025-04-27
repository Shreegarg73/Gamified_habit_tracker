<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitCompletion; // Import HabitCompletion
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon; // Import Carbon

class HabitController extends Controller
{
    /**
     * Display a listing of the user's habits and dashboard summary.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = $user->habits(); // Query for user's habits

        // --- Fetch Habits (with filtering) ---
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        $habits = $query->orderBy('created_at', 'desc')->get();

        // --- Fetch Dashboard Summary Data ---
        $totalHabits = $user->habits()->count(); // Count total habits

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY); // Assuming week starts Monday
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Count distinct habits completed today
        $completedTodayCount = HabitCompletion::where('user_id', $user->id)
            ->whereDate('completion_date', $today)
            ->distinct('habit_id')
            ->count();

        // Count distinct habits completed this week
        $completedThisWeekCount = HabitCompletion::where('user_id', $user->id)
            ->whereBetween('completion_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->distinct('habit_id')
            ->count();

        // Find the habit with the longest current streak for this user
        $longestStreakHabit = $user->habits()
            ->orderBy('current_streak', 'desc')
            ->first(); // Get the habit model or null


        // --- Prepare data for the view ---
        $categories = Habit::getCategories();

        return view('habits.index', [
            'habits' => $habits,
            'categories' => $categories,
            'selectedCategory' => $request->category,
            // Pass summary data to the view
            'totalHabits' => $totalHabits,
            'completedTodayCount' => $completedTodayCount,
            'completedThisWeekCount' => $completedThisWeekCount,
            'longestStreakHabit' => $longestStreakHabit, // Pass the whole habit model (or null)
        ]);
    }

    // ... (create, store, show, edit, update, destroy, markComplete methods remain the same) ...

    /**
     * Show the form for creating a new habit.
     */
    public function create(): View
    {
        return view('habits.create', [
            'categories' => Habit::getCategories(),
            'frequencies' => Habit::getFrequencies(),
        ]);
    }

    /**
     * Store a newly created habit in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', Habit::getCategories()),
            'frequency' => 'required|string|in:' . implode(',', Habit::getFrequencies()),
        ]);
        Auth::user()->habits()->create($validated);
        return redirect()->route('dashboard')->with('success', 'Habit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
         if (Auth::id() !== $habit->user_id) abort(403, 'Unauthorized action.');
         abort(404);
    }

    /**
     * Show the form for editing the specified habit.
     */
    public function edit(Habit $habit): View | RedirectResponse
    {
        if (Auth::id() !== $habit->user_id) abort(403, 'Unauthorized action.');
        return view('habits.edit', [
            'habit' => $habit,
            'categories' => Habit::getCategories(),
            'frequencies' => Habit::getFrequencies(),
        ]);
    }

    /**
     * Update the specified habit in storage.
     */
    public function update(Request $request, Habit $habit): RedirectResponse
    {
        if (Auth::id() !== $habit->user_id) abort(403, 'Unauthorized action.');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', Habit::getCategories()),
            'frequency' => 'required|string|in:' . implode(',', Habit::getFrequencies()),
        ]);
        $habit->update($validated);
        return redirect()->route('dashboard')->with('success', 'Habit updated successfully!');
    }

    /**
     * Remove the specified habit from storage.
     */
    public function destroy(Habit $habit): RedirectResponse
    {
         if (Auth::id() !== $habit->user_id) abort(403, 'Unauthorized action.');
         $habit->delete();
         return redirect()->route('dashboard')->with('success', 'Habit deleted successfully!');
    }

    /**
     * Mark the specified habit as complete for the current period (day/week).
     */
    public function markComplete(Request $request, Habit $habit): RedirectResponse
    {
        if (Auth::id() !== $habit->user_id) abort(403, 'Unauthorized action.');

        $today = Carbon::today();
        $message = "Habit already marked complete for this period.";
        $messageType = 'info';

        $alreadyCompleted = ($habit->frequency === 'daily')
            ? $habit->isCompletedToday()
            : $habit->isCompletedThisWeek();

        if (!$alreadyCompleted) {
            $habit->completions()->create([
                'user_id' => Auth::id(),
                'completion_date' => $today->toDateString(),
            ]);
            $habit->refresh()->updateStreak();

            $message = 'Habit marked as complete successfully!';
            $messageType = 'success';

            $updatedHabit = $habit->fresh();
            if ($updatedHabit->current_streak === 7) {
                 $message .= ' 🎉 Milestone unlocked: 7-Day Streak!';
            } elseif ($updatedHabit->current_streak === 30) {
                  $message .= ' 🏆 Milestone unlocked: 30-Day Streak!';
            }
        }

        return redirect()->route('dashboard')->with($messageType, $message);
    }
}
