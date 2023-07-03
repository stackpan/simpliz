<?php

namespace App\Services;

use App\Repositories\QuizRepository;

class QuizService
{
    
    public function __construct(
        private QuizRepository $quizRepository,
    ) {
        //
    }

    public function getAll()
    {
        return $this->quizRepository->getAll();
    }

    public function getById(string $id)
    {
        return $this->quizRepository->getById($id);
    }

}