<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HabitController; // Import the HabitController
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route for the homepage '/'
Route::get('/', function () {
    // If the user is logged in, redirect them to the dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Otherwise, show the default welcome page (which usually contains login/register links from Breeze)
    return view('welcome');
});


// Routes that require authentication (users must be logged in)
// The 'auth' middleware handles this. Breeze adds 'verified' middleware
// if email verification is enabled, ensuring the user's email is verified.
Route::middleware(['auth', 'verified'])->group(function () { // Added 'verified' middleware usually by Breeze

    // --- Dashboard Route ---
    // Points to the HabitController's index method to display habits
    Route::get('/dashboard', [HabitController::class, 'index'])->name('dashboard');

    // --- Profile Routes (from Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Habit Resource Routes ---
    // Creates standard routes for index, create, store, show, edit, update, destroy
    // Note: We don't explicitly use habits.index route as dashboard points there.
    // Note: We haven't implemented a view for habits.show.
    Route::resource('habits', HabitController::class)->except([
        // Optionally exclude routes if not needed, e.g., show if handled by dashboard
        // 'show'
    ]);

    // --- Custom Habit Action Route ---
    // Route for marking a habit as complete
    Route::post('/habits/{habit}/complete', [HabitController::class, 'markComplete'])
          ->name('habits.complete');

});


// Include Breeze's authentication routes (login, register, password reset, email verification etc.)
// This file (routes/auth.php) contains all routes necessary for the authentication flow.
require __DIR__.'/auth.php';
