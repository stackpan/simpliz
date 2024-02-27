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
    public function getPaginated(Quiz $quiz, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator;

    public function add(Quiz $quiz, string $participantId): bool;

    public function remove(Quiz $quiz, string $participantId): bool;
}
