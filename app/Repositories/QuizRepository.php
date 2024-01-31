<?php

namespace App\Repositories;

use App\Models\Participant;
use App\Models\Proctor;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizRepository
{
    public function getPaginatedByProctor(Proctor $proctor, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator;

    public function getPaginatedByParticipant(Participant $participant, ?string $search, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator;

    public function create(array $data, Proctor $proctor): Quiz;

    public function getById(string $id): ?Quiz;

    public function loadParticipantPivot(Quiz $quiz, Participant $participant): Quiz;

    public function update(Quiz $quiz, array $data): Quiz;

    public function delete(Quiz $quiz): string;
}
