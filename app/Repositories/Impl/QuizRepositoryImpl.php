<?php

namespace App\Repositories\Impl;

use App\Models\Participant;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizRepositoryImpl implements QuizRepository
{

    public function getPaginatedByProctor(Proctor $proctor, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        $query = $proctor->quizzes();
        if ($search) $query->whereFullText(['name'], $search);

        return $query->paginate($limit, page: $page);
    }

    public function getPaginatedByParticipant(Participant $participant, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        $query = $participant->quizzes();
        if ($search) $query->whereFullText(['name'], $search);

        return $query->paginate($limit, page: $page);
    }

    public function create(array $data, Proctor $proctor): Quiz
    {
        return $proctor->quizzes()->create($data);
    }

    public function getById(string $id): ?Quiz
    {
        return Quiz::find($id);
    }

    public function loadParticipantPivot(Quiz $quiz, Participant $participant): Quiz
    {
        return $participant->quizzes()->find($quiz->id);
    }

    public function update(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);
        return $quiz;
    }

    public function delete(Quiz $quiz): string
    {
        $quiz->delete();
        return $quiz->id;
    }

    public function getParticipants(Quiz $quiz, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        $query = $quiz->participants();

        if ($search)
            $query->whereHas('account', fn ($query) => $query
                ->whereFullText(['name', 'email', 'first_name', 'last_name'], $search));

        return $query->paginate($limit, page: $page);
    }
}
