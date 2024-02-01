<?php

namespace App\Services;

use App\Data\CreateQuizDto;
use App\Data\UpdateQuizDto;
use App\Models\Participant;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizService
{
    /**
     * @return LengthAwarePaginator<Quiz>
     */
    public function getPaginated(User $user, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator;

    public function create(CreateQuizDto $data, Proctor $creator): Quiz;

    public function get(Quiz $quiz, User $user): Quiz;

    public function update(Quiz $quiz, UpdateQuizDto $data): Quiz;

    public function delete(Quiz $quiz): string;

    /**
     * @return LengthAwarePaginator<Participant>
     */
    public function getParticipants(Quiz $quiz, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator;
}
