<?php

namespace App\Services;

use App\Repositories\ResultRepository;

class ResultService 
{
    
    public function __construct(
        private ResultRepository $resultRepository,
    ) {
    }

    public function getById(string $id)
    {
        return $this->resultRepository->getById($id);
    }

    public function store(string $userId, string $quizId)
    {
        return $this->resultRepository->store($userId, $quizId);
    }

}