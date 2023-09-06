<?php

namespace App\Services\Impl;

use App\Models\Quiz;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Database\Eloquent\Collection;

class QuizServiceImpl implements QuizService
{

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
