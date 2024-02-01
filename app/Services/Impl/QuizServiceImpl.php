<?php

namespace App\Services\Impl;

use App\Data\CreateQuizDto;
use App\Data\UpdateQuizDto;
use App\Enum\Color;
use App\Models\Participant;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Models\User;
use App\Repositories\QuizRepository;
use App\Services\QuizService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizServiceImpl implements QuizService
{
    public function __construct(private readonly QuizRepository $quizRepository)
    {
    }

    public function getPaginated(User $user, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator
    {
        switch ($user->accountable_type) {
            case Proctor::class:
                $quizzes = $this->quizRepository->getPaginatedByProctor($user->accountable, $search, $page, $limit);
                break;
            case Participant::class:
                $quizzes = $this->quizRepository->getPaginatedByParticipant($user->accountable, $search, $page, $limit);
                break;
        }
        return $quizzes;
    }

    public function create(CreateQuizDto $data, Proctor $creator): Quiz
    {
        $attributes = [
            'name' => $data->name,
            'description' => $data->description,
            'duration' => $data->duration,
            'max_attempts' => $data->maxAttempts,
            'color' => Color::fromName($data->color),
        ];

        return $this->quizRepository->create($attributes, $creator);
    }

    public function get(Quiz $quiz, User $user): Quiz
    {
        if ($user->accountable_type === Participant::class)
            $quiz = $this->quizRepository->loadParticipantPivot($quiz, $user->accountable);

        return $quiz;
    }

    public function update(Quiz $quiz, UpdateQuizDto $data): Quiz
    {
        $attributes = [
            'name' => $data->name,
            'description' => $data->description,
            'duration' => $data->duration,
            'max_attempts' => $data->maxAttempts,
            'color' => Color::fromName($data->color),
            'status' => $data->status,
        ];

        return $this->quizRepository->update($quiz, $attributes);
    }

    public function delete(Quiz $quiz): string
    {
        return $this->quizRepository->delete($quiz);
    }

    public function getParticipants(Quiz $quiz, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator
    {
        return $this->quizRepository->getParticipants($quiz, $search, $page, $limit);
    }
}
