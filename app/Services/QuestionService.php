<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Result;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuestionService
{

    public function getPaginatedByResult(Result $result): LengthAwarePaginator;

}
