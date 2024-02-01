<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuestionService
{
    /**
     * @return LengthAwarePaginator<Question>
     */
    public function getPaginatedByQuiz(Quiz $quiz, int $page, int $limit): LengthAwarePaginator;
}
