<?php

namespace App\Services\Impl;

use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use App\Services\QuestionService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionServiceImpl implements QuestionService
{
    public function __construct(private readonly QuestionRepository $questionRepository)
    {
    }

    public function getPaginatedByQuiz(Quiz $quiz, int $page, int $limit): LengthAwarePaginator
    {
        return $this->questionRepository->getPaginatedByQuiz($quiz, $page, $limit);
    }
}
