<?php

namespace App\Models;

use App\Models\Option;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\UserOption
 *
 * @property string $id
 * @property string $result_id
 * @property string $question_id
 * @property string|null $option_id
 * @property bool|null $is_correct
 * @property-read Option|null $option
 * @method static Builder|UserOption getByResultAndQuestion(string $resultId, string $questionId)
 * @method static Builder|UserOption newModelQuery()
 * @method static Builder|UserOption newQuery()
 * @method static Builder|UserOption query()
 * @method static Builder|UserOption whereId($value)
 * @method static Builder|UserOption whereIsCorrect($value)
 * @method static Builder|UserOption whereOptionId($value)
 * @method static Builder|UserOption whereQuestionId($value)
 * @method static Builder|UserOption whereResultId($value)
 * @mixin \Eloquent
 */
class UserOption extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'user_options';
    public $timestamps = false;

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function scopeGetByResultAndQuestion(Builder $query, string $resultId, string $questionId): \Illuminate\Database\Eloquent\Model
    {
        return $query->where('result_id', $resultId)
            ->where('question_id', $questionId)
            ->first();
    }

}
