<?php

namespace App\Models;

use App\Models\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'duration',
    ];

    /**
     * Get all of the Question for the Quiz
     *
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * 
     */
    public function questionsWithResultData(Result $result)
    {
        return $this->questions()
            ->with(['resultQuestions' => function (HasMany $query) use ($result) {
                $query->where('result_id', $result->id);
            }]);
    }

    /**
     * The User that belong to the Quiz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all of the Result for the Quiz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

}