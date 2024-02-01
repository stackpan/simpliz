<?php

namespace App\Repositories\Impl;

use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionRepositoryImpl implements QuestionRepository
{
    public function getPaginatedByQuiz(Quiz $quiz, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        return $quiz->questions()->paginate($limit, page: $page);
    }
}
