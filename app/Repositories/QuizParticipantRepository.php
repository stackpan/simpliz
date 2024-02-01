<?php

namespace App\Repositories;

use App\Models\Participant;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizParticipantRepository
{
    public function checkExistenceByParticipantId(Quiz $quiz, string $participantId): bool;

    /**
     * @return LengthAwarePaginator<Participant>
     */
    public function getParticipants(Quiz $quiz, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator;
}
