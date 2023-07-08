<?php

namespace App\Models;

use App\Models\Option;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOption extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'user_options';
    public $timestamps = false;

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

}