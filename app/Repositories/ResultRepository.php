<?php

namespace App\Repositories;

use App\Models\Result;
use Illuminate\Database\Eloquent\Collection;

class ResultRepository
{

    public function __construct(
        private Result $model,
    )
    {
        //
    }

    public function getById(string $id): ?Result
    {
        return $this->model->find($id);
    }

    public function getByUserIdAndQuizId(string $userId, string $quizId)
    {
        return $this->model->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->get();
    }

    public function setFinishedById(string $id)
    {
        $result = $this->model->find($id);

        $result->finished_at = $this->model->freshTimestamp();

        $result->save();
    }

    public function getByQuizAndUser(string $quizId, string $userId): Collection
    {
        return $this->model->where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->get();
    }

    public function create(string $userId, string $quizId): Result
    {
        $result = new Result;

        $result->user_id = $userId;
        $result->quiz_id = $quizId;
        $result->created_at = $this->model->freshTimestamp();
        $result->save();
    
        return $result;
    }

}