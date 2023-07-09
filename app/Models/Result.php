<?php

namespace App\Models;

use App\Models\Question;
use App\Models\UserOption;
use App\Models\ResultQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Result extends Model
{
    use HasFactory, HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
    ];

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

    public function questions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Question::class, 'user_options')
            ->using(UserOption::class);
    }

    public function setCompleted()
    {
        $totalCorrectAnswers = $this
            ->questions()
            ->wherePivot('is_correct', true)
            ->count();

        $totalQuestions = $this
            ->quiz
            ->questions
            ->count();

        $score = round(($totalCorrectAnswers / $totalQuestions) * 100, 1);

        $this->completed_at = $this->freshTimestamp();
        $this->score = $score;

        $this->save();
    }

    public function scopeWithDetails(Builder $query)
    {
        return $query
            ->with([
                'quiz' => fn($query) => $query
                    ->withCount('questions')
            ])
            ->with('user');
    }
    
}