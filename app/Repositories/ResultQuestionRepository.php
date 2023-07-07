<?php

namespace App\Repositories;

use App\Models\ResultQuestion;

class ResultQuestionRepository
{

    public function __construct(
        private ResultQuestion $model,
    ) {
        //
    }

    public function create(string $resultId, string $questionId): ResultQuestion
    {
        $resultQuestion = new ResultQuestion;

        $resultQuestion->result_id = $resultId;
        $resultQuestion->question_id = $questionId;

        $resultQuestion->save();

        return $resultQuestion;
    }

}