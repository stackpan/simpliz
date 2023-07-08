<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizService
{
    
    public function __construct(
        private Quiz $quiz,
    ) {
        //
    }

    public function getAll()
    {
        return $this->quiz
            ->select('id', 'name', 'duration')
            ->withQuestionsCount()
            ->get();
    }

    public function getDetail(string $quizId)
    {
        return $this->quiz
            ->select('id', 'name', 'description', 'duration')
            ->withQuestionsCount()
            ->withUserResults(auth()->user())
            ->find($quizId);
    }

}