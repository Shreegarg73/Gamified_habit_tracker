<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Import HasMany

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define the one-to-many relationship with Habit.
     * Get all of the habits for the User.
     *
     * This method defines the relationship between the User model and the Habit model.
     * It specifies that one User can have 'many' Habits.
     */
    public function habits(): HasMany // <-- Add this method
    {
        // By convention, Laravel assumes the foreign key in the 'habits' table
        // is 'user_id' (based on the model name 'User' + '_id').
        // If it were different, you'd specify it as the second argument.
        return $this->hasMany(Habit::class);
    }
}
