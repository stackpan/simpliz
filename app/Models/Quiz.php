<?php

namespace App\Models;

use App\Enum\Color;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Quiz
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $duration
 * @property int|null $max_attempts
 * @property Color $color
 * @property string $status
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Proctor|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Participant> $participants
 * @property-read int|null $participants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereMaxAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereUpdatedAt($value)
 * @method static \Database\Factories\QuizFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Quiz extends Model
{
    use HasFactory, HasUuids;

    protected $with = ['createdBy'];

    protected $casts = [
        'color' => Color::class,
    ];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class)->withPivot('highest_score', 'attempt_count');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Proctor::class, 'created_by');
    }
}
