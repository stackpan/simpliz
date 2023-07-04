<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository 
{

    public function __construct(
        private Question $question,
    ) {
        //
    }

    public function getPaginatedByQuizId(string $quizId)
    {
        return $this->question->select('id', 'context', 'body')
            ->with('options')
            ->where('quiz_id', $quizId)
            ->simplePaginate(1);
    }

}