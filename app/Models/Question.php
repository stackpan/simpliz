<?php

namespace App\Models;

use App\Models\ResultQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Question
 *
 * @property string $id
 * @property string $quiz_id
 * @property string|null $context
 * @property string $body
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Option> $options
 * @property-read int|null $options_count
 * @property-read \App\Models\Quiz $quiz
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question query()
 * @method static Builder|Question whereBody($value)
 * @method static Builder|Question whereContext($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereQuizId($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'context',
        'body',
    ];

    /**
     * Get the Quiz that owns the Question
     *
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get all of the Option for the Question
     *
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

}
