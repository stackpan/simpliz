<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizParticipantService
{
    public function checkAuthorization(Quiz $quiz, string $participantId): bool;

    /**
     * @return LengthAwarePaginator<Participant>
     */
    public function getPaginated(Quiz $quiz, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator;

    public function add(Quiz $quiz, string $participantId): bool;

    public function remove(Quiz $quiz, string $participantId): bool;
}
