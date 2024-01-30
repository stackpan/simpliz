<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Participant
 *
 * @property string $id
 * @property-read \App\Models\User|null $account
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @method static \Database\Factories\ParticipantFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Participant extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $with = ['account'];

    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'accountable');
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class)->withPivot('highest_score', 'attempt_count');
    }
}
