<?php

namespace App\Services\Impl;

use App\Models\Quiz;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Database\Eloquent\Collection;

class QuizServiceImpl implements QuizService
{

    public function getAll(?User $user = null, bool $userCount = false): Collection
    {
        $quizzes = Quiz::withQuestionsCount();

        if ($user) {
            $quizzes = $quizzes
                ->whereUser($user)
                ->has('questions');
        }

        if ($userCount) {
            $quizzes = $quizzes->withCount('users');
        }

        return $quizzes->get('quizzes.id', 'name', 'duration');
    }

    public function loadDetails(Quiz $quiz): Quiz
    {
        return $quiz
            ->loadQuestionCount()
            ->loadUserResults(auth()->user());
    }

}
