<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabitCompletion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'habit_id',
        'user_id',
        'completion_date',
    ];

    /**
     * The attributes that should be cast.
     * Casts the completion_date column to a Carbon date object.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completion_date' => 'date', // Automatically cast to Carbon date object
    ];

    /**
     * Get the habit that this completion belongs to.
     */
    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    /**
     * Get the user that this completion belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
