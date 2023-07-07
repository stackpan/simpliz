<?php

namespace App\Repositories;

use App\Models\QuizSession;

class QuizSessionRepository
{

    public function __construct(
        private QuizSession $model,
    ) {
        //
    }

    public function getById(string $id): QuizSession
    {
        
    }

}