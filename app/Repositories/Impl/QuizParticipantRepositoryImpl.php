<?php

namespace App\Repositories\Impl;

use App\Models\Quiz;
use App\Repositories\QuizParticipantRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizParticipantRepositoryImpl implements QuizParticipantRepository
{

    public function checkExistenceByParticipantId(Quiz $quiz, string $participantId): bool
    {
        return $quiz->participants()->where('participant_id', $participantId)->exists();
    }

    public function getPaginated(Quiz $quiz, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        $query = $quiz->participants();

        if ($search)
            $query->whereHas('account', fn ($query) => $query
                ->whereFullText(['name', 'email', 'first_name', 'last_name'], $search));

        return $query->paginate($limit, page: $page);
    }

    public function add(Quiz $quiz, string $participantId): bool
    {
        $quiz->participants()->attach($participantId);
        return true;
    }

    public function remove(Quiz $quiz, string $participantId): bool
    {
        $quiz->participants()->detach($participantId);
        return true;
    }
}
