<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuestionRepository
{
    /**
     * @return LengthAwarePaginator<Question>
     */
    public function getPaginatedByQuiz(Quiz $quiz, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator;

    public function create(Quiz $quiz, array $data): Question;

    public function loadDetails(Question $question): Question;
}
