<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Pagination\LengthAwarePaginator;

class ResultService
{

    public function __construct(
        private Result $model,
    ) {
        //
    }

    public function loadDetails(Result $result): Result
    {
        return $result->loadRelations();
    }

}