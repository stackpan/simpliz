<?php

namespace App\Services\Impl;

use App\Models\UserOption;
use App\Services\UserOptionService;

class UserOptionServiceImpl implements UserOptionService
{

    /**
     * Find UserOption by Result id and Question id
     */
    public function getByForeigns(string $resultId, string $questionId): ?UserOption
    {
        return UserOption::getByResultAndQuestion($resultId, $questionId);
    }

}
