<?php

namespace App\Services;

use App\Repositories\QuestionRepository;

class QuestionService 
{
    
    public function __construct(
        private QuestionRepository $questionRepository,
    ) {
        //
    }

    public function getPaginatedByQuizId(string $quizId)
    {
        return $this->questionRepository->getPaginatedByQuizId($quizId);
    }
}