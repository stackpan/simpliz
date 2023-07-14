<?php

namespace App\Models;

use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function scopeWithQuestionsCount(Builder $query): Builder
    {
        return $query->withCount('questions');
    }

    public function scopeWithUserResults(Builder $query, User $user): Builder
    {
        return $query->with([
            'results' => fn($query) => $query
                    ->where('user_id', $user->id)
                    ->latest()
                    ->with('quizSession'),
        ]);
    }

}