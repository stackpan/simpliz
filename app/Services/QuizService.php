<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
                ->has('questions')
                ->withQuestionsCount()
                ->get('quizzes.id', 'name', 'duration');
        }

        return $this->model
            ->select('id', 'name', 'duration')
            ->withQuestionsCount()
            ->get();
    }

    /**
     * @deprecated
     */
    public function getDetail(string $quizId)
    {
        return $this->model
            ->select('id', 'name', 'description', 'duration')
            ->withQuestionsCount()
            ->withUserResults(auth()->user())
            ->find($quizId);
    }

    public function loadDetail(Quiz $quiz)
    {
        return $quiz
            ->loadCount('questions')
            ->load([
                'results' => fn (Builder $query) => $query
                    ->select('id', 'quiz_id', 'user_id', 'score', 'completed_at')
                    ->whereBelongsTo(auth()->user())
                    ->with('quizSession'),
            ]);
    }

}