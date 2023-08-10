<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizSession extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ends_at' => 'datetime',
    ];

    /**
     * Get the result that owns the QuizSession
     *
     * @return BelongsTo
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    public function scopeWithDetails(Builder $query)
    {
        return $query
            ->with(['result.questions' => fn($query) => $query
                ->with('options')
                ->withPivot('id', 'option_id')
                ->paginate(1),
            ]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->result->user->is($user);
    }

    public function isTimeout(): bool
    {
        return now()->greaterThan($this->ends_at);
    }
}
