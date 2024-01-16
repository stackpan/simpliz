<?php

namespace App\Repository\Impl;

use App\Data\GeneralQuizData;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Repository\QuizRepository;
use Illuminate\Database\Eloquent\Collection;

class QuizRepositoryImpl implements QuizRepository
{

    public function getAllByAuthorId(string $authorId): Collection
    {
        return Quiz::whereCreatedBy($authorId)
            ->get();
    }

    public function create(GeneralQuizData $data, Proctor $author): Quiz
    {
        $quiz = new Quiz((array) $data);

        $author->quizzes()->save($quiz);

        return $quiz;
    }

    public function update(Quiz $quiz, GeneralQuizData $data): void
    {
        $quiz->update((array) $data);
    }

    public function delete(Quiz $quiz): void
    {
        $quiz->delete();
    }
}
