<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\Participant
 *
 * @property int $id
 * @property-read \App\Models\User|null $account
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereId($value)
 * @mixin \Eloquent
 */
class Participant extends Model
{
    use HasFactory;

    protected $with = ['account'];

    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'accountable');
    }
}
