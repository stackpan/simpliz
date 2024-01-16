<?php

namespace App\Service\Impl;

use App\Data\GeneralQuizData;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Repository\QuizRepository;
use App\Service\QuizService;
use Illuminate\Database\Eloquent\Collection;

class QuizServiceImpl implements QuizService
{
    public function __construct(private readonly QuizRepository $quizRepository)
    {
    }

    public function getAllByAuthor(Proctor $author): Collection
    {
        return $this->quizRepository->getAllByAuthorId($author->id);
    }

    public function create(GeneralQuizData $data, Proctor $author): Quiz
    {
        return $this->quizRepository->create($data, $author);
    }

    public function update(Quiz $quiz, GeneralQuizData $data): void
    {
        $this->quizRepository->update($quiz, $data);
    }

    public function delete(Quiz $quiz): void
    {
        $this->quizRepository->delete($quiz);
    }
}
