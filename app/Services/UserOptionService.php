<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Question;
use App\Models\UserOption;

class UserOptionService
{
    public function __construct(
        private UserOption $model,
    ) {
        //
    }

    /**
     * Find UserOption by Result id and Question id
     */
    public function getByForeigns(string $resultId, string $questionId): ?UserOption
    {
        return $this->model->findByResultAndQuestion($resultId, $questionId);
    }

}
