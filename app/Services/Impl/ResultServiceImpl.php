<?php

namespace App\Services\Impl;

use App\Models\Result;
use App\Services\ResultService;

class ResultServiceImpl implements ResultService
{

    public function loadDetails(Result $result): Result
    {
        return $result->loadRelations();
    }

}
