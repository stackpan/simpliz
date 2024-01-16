<?php

namespace App\Repository;

use App\Data\GeneralQuizData;
use App\Models\Proctor;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

interface QuizRepository
{
    public function getAllByAuthorId(string $authorId): Collection;

    public function create(GeneralQuizData $data, Proctor $author): Quiz;

    public function update(Quiz $quiz, GeneralQuizData $data): void;

    public function delete(Quiz $quiz): void;
}
