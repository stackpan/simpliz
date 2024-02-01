<?php

namespace App\Services\Impl;

use App\Models\Quiz;
use App\Repositories\QuizParticipantRepository;
use App\Services\QuizParticipantService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizParticipantServiceImpl implements QuizParticipantService
{
    public function __construct(private readonly QuizParticipantRepository $quizParticipantRepository)
    {
    }

    public function checkAuthorization(Quiz $quiz, string $participantId): bool
    {
        return $this->quizParticipantRepository->checkExistenceByParticipantId($quiz, $participantId);
    }

    public function getPaginated(Quiz $quiz, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator
    {
        return $this->quizParticipantRepository->getParticipants($quiz, $search, $page, $limit);
    }
}
