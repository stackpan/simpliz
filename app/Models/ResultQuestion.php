<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultQuestion extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    /**
     * Get the userOption associated with the ResultQuestion
     *
     * @return HasOne
     */
    public function userOption(): HasOne
    {
        return $this->hasOne(UserOption::class);
    }

}
