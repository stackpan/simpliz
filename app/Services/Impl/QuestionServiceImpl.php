<?php

namespace App\Services\Impl;

use App\Data\QuestionDto;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use App\Services\QuestionService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionServiceImpl implements QuestionService
{
    public function __construct(private readonly QuestionRepository $questionRepository)
    {
    }

    public function getPaginatedByQuiz(Quiz $quiz, int $page, int $limit): LengthAwarePaginator
    {
        return $this->questionRepository->getPaginatedByQuiz($quiz, $page, $limit);
    }

    public function create(Quiz $quiz, QuestionDto $data): Question
    {
        $attributes = [
            'body' => $data->body,
        ];

        return $this->questionRepository->create($quiz, $attributes);
    }

    public function get(Question $question): Question
    {
        return $this->questionRepository->loadDetails($question);
    }

    public function update(Question $question, QuestionDto $data): Question
    {
        $attributes = [
            'body' => $data->body,
        ];

        return $this->questionRepository->update($question, $attributes);
    }

    public function delete(Question $question): string
    {
        return $this->questionRepository->delete($question);
    }

    public function setAnswer(Question $question, string $optionId): bool
    {
        return $this->questionRepository->setAnswer($question, $optionId);
    }

    public function checkOptionExistence(Question $question, string $optionId): bool
    {
        return $this->questionRepository->checkOptionExistenceById($question, $optionId);
    }
}
