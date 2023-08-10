<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

class QuizService
{
    
    public function __construct(
        private Quiz $model,
    ) {
        //
    }

    public function getAll(User $user): Collection
    {
        return $user->quizzes()
                ->has('questions')
                ->withQuestionsCount()
                ->get('quizzes.id', 'name', 'duration');
    }

    public function loadDetails(Quiz $quiz): Quiz
    {
        return $quiz
            ->loadQuestionCount()
            ->loadUserResults(auth()->user());
    }

}