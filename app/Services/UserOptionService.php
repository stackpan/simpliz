<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Question;
use App\Models\UserOption;

class UserOptionService
{
    public function __construct(
        private UserOption $userOption,
    ) {
        //
    }

    /**
     * Find UserOption by Result id and Question id
     */
    public function getByForeigns(Result $result, Question $question): ?UserOption
    {
        return $this->userOption->findByResultAndQuestion($result->id, $question->id);
    }

}
