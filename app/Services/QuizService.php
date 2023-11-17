<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface QuizService
{

    public function getAll(?User $user, bool $userCount): Collection;

    public function loadDetails(Quiz $quiz): Quiz;

    public function getById(string $id): ?Quiz;

    public function create(string $title, string $description, int $duration): ?string;

    public function update(Quiz $quiz, string $title, string $description, int $duration): bool;
}
