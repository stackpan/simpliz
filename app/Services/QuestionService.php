<?php

namespace App\Services;

use App\Data\QuestionDto;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuestionService
{
    /**
     * @return LengthAwarePaginator<Question>
     */
    public function getPaginatedByQuiz(Quiz $quiz, int $page, int $limit): LengthAwarePaginator;

    public function create(Quiz $quiz, QuestionDto $data): Question;

    public function get(Question $question): Question;

    public function update(Question $question, QuestionDto $data): Question;

    public function delete(Question $question): string;

    public function setAnswer(Question $question, string $optionId): bool;

    public function checkOptionExistence(Question $question, string $optionId): bool;
}
