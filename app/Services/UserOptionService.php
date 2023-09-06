<?php

namespace App\Services;

use App\Models\UserOption;

interface UserOptionService
{

    /**
     * Find UserOption by Result id and Question id
     */
    public function getByForeigns(string $resultId, string $questionId): ?UserOption;

}
