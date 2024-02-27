<?php

namespace App\Services\Impl;

use App\Repositories\UserRepository;
use App\Services\ParticipantService;
use Illuminate\Database\Eloquent\Collection;

class ParticipantServiceImpl implements ParticipantService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function search(string $keyword, int $limit): Collection
    {
        return $this->userRepository->searchParticipants($keyword, $limit);
    }
}
