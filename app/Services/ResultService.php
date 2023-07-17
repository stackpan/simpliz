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

    public function getById(string $id, bool $withDetail): ?Result
    {
        $result = $this->model;

        if ($withDetail) {
            $result = $result->withDetails();
        }

        return $result->find($id);
    }

    public function getPaginatedQuestionsResult(Result $result): LengthAwarePaginator
    {
        return $result
            ->questions()
            ->withPivot('id', 'option_id', 'is_correct')
            ->paginate(10);
    }

}