<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Result extends Model
{
    use HasFactory, HasUuids;

    /**
     * Get all of the UserOption for the Result
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userOptions(): HasMany
    {
        return $this->hasMany(UserOption::class);
    }
}