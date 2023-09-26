<?php

namespace App\Models;

use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Quiz
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property int $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Result> $results
 * @property-read int|null $results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\QuizFactory factory($count = null, $state = [])
 * @method static Builder|Quiz newModelQuery()
 * @method static Builder|Quiz newQuery()
 * @method static Builder|Quiz query()
 * @method static Builder|Quiz whereCreatedAt($value)
 * @method static Builder|Quiz whereDescription($value)
 * @method static Builder|Quiz whereDuration($value)
 * @method static Builder|Quiz whereId($value)
 * @method static Builder|Quiz whereName($value)
 * @method static Builder|Quiz whereUpdatedAt($value)
 * @method static Builder|Quiz withQuestionsCount()
 * @property bool $is_enabled
 * @method static Builder|Quiz whereIsEnabled($value)
 * @mixin \Eloquent
 */
class Quiz extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'duration',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * @deprecated
     */
    public function scopeWithQuestionsCount(Builder $query): Builder
    {
        return $query->withCount('questions');
    }

    public function loadQuestionCount(): Quiz
    {
        return $this->loadCount('questions');
    }

    public function loadUserResults(User $user): Quiz
    {
        return $this->load([
            'results' => fn ($query) => $query
                ->select('id', 'quiz_id', 'user_id', 'score', 'completed_at')
                ->whereBelongsTo($user)
                ->latest()
                ->with('quizSession'),
        ]);
    }
}
