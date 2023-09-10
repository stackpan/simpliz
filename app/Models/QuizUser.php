<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\QuizUser
 *
 * @property string $id
 * @property string $user_id
 * @property string $quiz_id
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUser whereUserId($value)
 * @mixin \Eloquent
 */
class QuizUser extends Pivot
{
    use HasFactory, HasUuids;
}
