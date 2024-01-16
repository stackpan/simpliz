<?php

namespace App\Service;

use App\Data\GeneralQuizData;
use App\Models\Proctor;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

interface QuizService
{
    public function getAllByAuthor(Proctor $author): Collection;

    public function create(GeneralQuizData $data, Proctor $author): Quiz;

    public function update(Quiz $quiz, GeneralQuizData $data): void;

    public function delete(Quiz $quiz): void;
}
