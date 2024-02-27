<?php

namespace App\Repositories\Impl;

use App\Models\Participant;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserRepositoryImpl implements UserRepository
{
    public function searchParticipants(string $keyword, int $limit = 10): Collection
    {
        return User::whereFullText(['name', 'email', 'first_name', 'last_name'], $keyword)->whereAccountableType(Participant::class)
            ->with('accountable')
            ->limit($limit)
            ->get();
    }
}
