<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory, HasUuids;
    
    public $timestamps = false;
    
    protected $fillable = [
        'finished_at',
    ];

    /**
     * Get all of the UserOption for the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userOptions(): HasMany
    {
        return $this->hasMany(UserOption::class);
    }

    /**
     * Get the user that owns the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz that owns the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

}