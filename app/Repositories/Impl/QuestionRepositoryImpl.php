<?php

namespace App\Repositories\Impl;

use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionRepositoryImpl implements QuestionRepository
{
    public function getPaginatedByQuiz(Quiz $quiz, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        return $quiz->questions()->with('options')->paginate($limit, page: $page);
    }

    public function create(Quiz $quiz, array $data): Question
    {
        return $quiz->questions()->create($data);
    }

    public function loadDetails(Question $question): Question
    {
        return $question->load('options');
    }
}
