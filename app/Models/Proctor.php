<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Proctor
 *
 * @property string $id
 * @property-read \App\Models\User|null $account
 * @method static \Illuminate\Database\Eloquent\Builder|Proctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proctor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proctor whereId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @method static \Database\Factories\ProctorFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Proctor extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $with = ['account'];

    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'accountable');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }
}
