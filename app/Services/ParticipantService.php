<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ParticipantService
{
    /**
     * @return Collection<User>
     */
    public function search(string $keyword, int $limit): Collection;
}
