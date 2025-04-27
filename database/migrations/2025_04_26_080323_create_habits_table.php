<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Creates the 'habits' table
        Schema::create('habits', function (Blueprint $table) {
            $table->id(); // Adds an auto-incrementing primary key column named 'id'

            // Foreign key to link habits to users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // - foreignId('user_id'): Creates an unsigned big integer column for the user ID.
            // - constrained(): Automatically sets up the foreign key constraint referencing the 'id' column on the 'users' table.
            // - onDelete('cascade'): If a user is deleted, all their associated habits will also be deleted.

            $table->string('title'); // Column to store the habit's name (e.g., "Drink Water", "Exercise")
            $table->text('description')->nullable(); // Optional longer description for the habit
            $table->string('category'); // Type of habit (e.g., "Health", "Productivity", "Learning")
            $table->string('frequency'); // How often? (e.g., "daily", "weekly") - We'll handle logic later
            $table->integer('current_streak')->default(0); // Tracks consecutive completions
            $table->integer('longest_streak')->default(0); // Stores the best streak achieved
            // We will add more progress tracking later if needed, maybe completion logs

            $table->timestamps(); // Adds 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits'); // Defines how to undo the migration (delete the table)
    }
};
