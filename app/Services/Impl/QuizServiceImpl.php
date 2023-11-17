<?php

namespace App\Services\Impl;

use App\Models\Quiz;
use App\Models\User;
use App\Dto\QuizUpdateDto;
use App\Services\QuizService;
use Illuminate\Database\Eloquent\Collection;

class QuizServiceImpl implements QuizService
{

    public function getAll(?User $user = null, bool $userCount = false): Collection
    {
        $quizzes = Quiz::select('quizzes.id', 'name', 'duration')->withQuestionsCount();

        if ($user) {
            $quizzes = $quizzes
                ->whereUser($user)
                ->has('questions');
        }

        if ($userCount) {
            $quizzes = $quizzes->withCount('users');
        }

        return $quizzes->get();
    }

    public function loadDetails(Quiz $quiz): Quiz
    {
        return $quiz
            ->loadQuestionCount()
            ->loadUserResults(auth()->user());
    }

    public function getById(string $id): ?Quiz
    {
        return Quiz::find($id);
    }

    public function create(string $title, string $description, int $duration): ?string
    {
        $quiz = Quiz::create([
            'title' => $title,
            'description' => $description,
            'duration' => $duration,
        ]);

        return $quiz->id;
    }

    public function update(Quiz $quiz, QuizUpdateDto $dto): bool
    {
        $quiz->fill([
            'title' => $dto->title,
            'description' => $dto->description,
            'duration' => $dto->duration,
        ])->save();

        return $quiz->wasChange();
    }

}
