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
        return $this->quiz->select('id', 'name', 'duration')
            ->withCount('questions')
            ->get();
    }

    public function getById(string $id)
    {
        return $this->quiz->select('id', 'name', 'duration', 'description')
            ->withCount('questions')
            ->find($id);
    }

}