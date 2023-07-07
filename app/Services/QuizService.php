<?php

namespace App\Services;

use App\Models\Result;
use App\Repositories\QuizRepository;
use App\Repositories\ResultRepository;

class QuizService
{
    
    public function __construct(
        private QuizRepository $quizRepository,
        private ResultRepository $resultRepository,
    ) {
        //
    }

    public function getAll()
    {
        return $this->quizRepository->getAll();
    }

    public function getDetail(string $quizId)
    {
        $userResults = $this->resultRepository->getByQuizAndUser($quizId, auth()->user()->id);

        // get last quiz session from incompleted user result if available
        $lastQuizSession = optional($userResults->first(fn(Result $result, int $key) => $result->completed_at === null))->quiz_session;

        return collect([
            'quiz' => $this->quizRepository->getById($quizId),
            'userResults' => $userResults,
            'lastQuizSession' => $lastQuizSession,
        ]);
    }

}