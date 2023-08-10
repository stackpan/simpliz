<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Question;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionService {

    public function __construct(
        private Question $model,
    ) {
        //
    }

    public function getPaginatedByResult(Result $result): LengthAwarePaginator
    {
        return $result
            ->questions()
            ->withPivot('id', 'option_id', 'is_correct')
            ->paginate(10);
    }

}