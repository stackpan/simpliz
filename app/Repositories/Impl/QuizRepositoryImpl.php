<?php

namespace App\Repositories\Impl;

use App\Models\Participant;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
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
}
