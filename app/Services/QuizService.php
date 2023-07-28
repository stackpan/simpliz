<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizService
{
    
    public function __construct(
        private Quiz $model,
    ) {
        //
    }

    public function getAll(?User $user)
    {
        if ($user) {
            return $user->quizzes()
                ->withQuestionsCount()
                ->get('quizzes.id', 'name', 'duration');
        }

        return $this->model
            ->select('id', 'name', 'duration')
            ->withQuestionsCount()
            ->get();
    }

    public function getDetail(string $quizId)
    {
        return $this->model
            ->select('id', 'name', 'description', 'duration')
            ->withQuestionsCount()
            ->withUserResults(auth()->user())
            ->find($quizId);
    }

}