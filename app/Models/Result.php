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
        'completed_at',
        'completed_duration',
        'score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
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

    /**
     * The questions that belong to the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
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

        $completed_at = $this->freshTimestamp();
        $completed_duration = $this->created_at->diffInMilliseconds(
            $completed_at->greaterThan($this->quizSession->ends_at) 
            ? $this->quizSession->ends_at 
            : $this->completed_at
        );
        $score = round(($totalCorrectAnswers / $totalQuestions) * 100, 1);

        $this->fill([
            'completed_at' => $completed_at,
            'completed_duration' => $completed_duration,
            'score' => $score,
        ])->save();
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