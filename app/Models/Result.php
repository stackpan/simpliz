<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory, HasUuids;
    
    public $timestamps = false;

    /**
     * Get all of the UserOption for the Result
     *
     * @return HasMany
     */
    public function userOptions(): HasMany
    {
        return $this->hasMany(UserOption::class);
    }

    /**
     * Get the user that owns the Result
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz that owns the Result
     *
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the quizSession associated with the Result
     *
     * @return HasOne
     */
    public function quizSession(): HasOne
    {
        return $this->hasOne(QuizSession::class);
    }

}