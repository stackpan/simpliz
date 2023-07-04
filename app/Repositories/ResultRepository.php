<?php

namespace App\Repositories;

use App\Models\Result;

class ResultRepository
{

    public function __construct(
        private Result $result,
    )
    {
        //
    }

    public function getById(string $id): ?Result
    {
        return $this->result->find($id);
    }

    public function getByUserIdAndQuizId(string $userId, string $quizId)
    {
        return $this->result->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->get();
    }

    public function store(string $userId, string $quizId)
    {
        $result = new Result;

        $result->user_id = $userId;
        $result->quiz_id = $quizId;
        $result->created_at = $this->result->freshTimestamp();

        $result->save();
        
        return $result->id;
    }

}