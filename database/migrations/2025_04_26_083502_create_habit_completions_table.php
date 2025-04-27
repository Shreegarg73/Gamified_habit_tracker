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
        Schema::create('habit_completions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key

            // Foreign key to the habits table
            $table->foreignId('habit_id')->constrained()->onDelete('cascade');
            // - constrained(): Links to 'id' on 'habits' table
            // - onDelete('cascade'): If the habit is deleted, its completion records are also deleted.

            // Foreign key to the users table (denormalized for easier queries, optional but can be handy)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // - constrained(): Links to 'id' on 'users' table
            // - onDelete('cascade'): If the user is deleted, their completion records are also deleted.

            // The date the habit was marked as completed
            $table->date('completion_date');

            $table->timestamps(); // created_at and updated_at

            // Add an index for faster lookups by habit and date
            $table->index(['habit_id', 'completion_date']);
            // Add an index for user and date lookups
            $table->index(['user_id', 'completion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_completions');
    }
};
