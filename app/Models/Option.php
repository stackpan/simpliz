<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Option
 *
 * @property string $id
 * @property string $question_id
 * @property string $body
 * @property bool $is_answer
 * @property-read \App\Models\Answer|null $answer
 * @property-read \App\Models\Question $question
 * @method static \Database\Factories\OptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option query()
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereIsAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereQuestionId($value)
 * @mixin \Eloquent
 */
class Option extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'body',
    ];

    /**
     * Get the Question that owns the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the answer associated with the Option
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class);
    }
}