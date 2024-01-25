<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    /**
     * @return Collection<User>
     */
    public function searchParticipants(string $keyword, int $limit = 10): Collection;
}
