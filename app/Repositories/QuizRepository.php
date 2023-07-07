<?php

namespace App\Repositories;

use App\Models\Quiz;

class QuizRepository
{
    
    public function __construct(
        private Quiz $quiz,
    )
    {
        //
    }

    public function getAll()
    {
        return $this->quiz->withCount('questions')
            ->get();
    }

    public function getById(string $id)
    {
        return $this->quiz->withCount('questions')
            ->find($id);
    }

}