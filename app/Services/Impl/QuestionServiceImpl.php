<?php

namespace App\Services\Impl;

use App\Models\Question;
use App\Models\Result;
use App\Services\QuestionService;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionServiceImpl implements QuestionService
{

    public function getPaginatedByResult(Result $result): LengthAwarePaginator
    {
        return $result
            ->questions()
            ->withPivot('id', 'option_id', 'is_correct')
            ->with('options')
            ->paginate(10);
    }

}
