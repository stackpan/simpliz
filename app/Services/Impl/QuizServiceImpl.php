<?php

namespace App\Services\Impl;

use App\Models\Participant;
use App\Models\Proctor;
use App\Models\User;
use App\Repositories\QuizRepository;
use App\Services\QuizService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizServiceImpl implements QuizService
{
    public function __construct(private readonly QuizRepository $quizRepository)
    {
    }

    public function getPaginated(User $user, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator
    {
        switch ($user->accountable_type) {
            case Proctor::class:
                $quizzes = $this->quizRepository->getPaginatedByProctor($user->accountable, $search, $page, $limit);
                break;
            case Participant::class:
                $quizzes = $this->quizRepository->getPaginatedByParticipant($user->accountable, $search, $page, $limit);
                break;
        }
        return $quizzes;
    }
}
