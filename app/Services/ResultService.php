<?php

namespace App\Services;

use App\Models\Option;
use App\Models\Result;
use App\Models\UserOption;
use App\Repositories\OptionRepository;
use App\Repositories\ResultRepository;
use App\Repositories\UserOptionRepository;

class ResultService 
{
    
    public function __construct(
        private ResultRepository $resultRepository,
        private OptionRepository $optionRepository,
        private UserOptionRepository $userOptionRepository,
    ) {
    }

    public function getById(string $id): ?Result
    {
        return $this->resultRepository->getById($id);
    }

    public function getUserResultsByQuizId(string $userId, string $quizId)
    {
        return $this->resultRepository->getByUserIdAndQuizId($userId, $quizId);
    }

    public function store(string $userId, string $quizId)
    {
        return $this->resultRepository->store($userId, $quizId);
    }

    public function saveUserOption(string $resultId, string $optionId)
    {
        $previousUserOption = $this->getPreviousUserOption($resultId, $optionId);

        if (isset($previousUserOption)) {
            return $this->userOptionRepository->updateById($previousUserOption->id, $optionId);
        }

        return $this->userOptionRepository->store($resultId, $optionId);
    }

    /**
     * Get UserOption by resultId and optionId
     */
    public function getUserOption(string $resultId, string $optionId): ?UserOption
    {
        return $this->userOptionRepository->getByResultIdAndOptionId($resultId, $optionId);
    }

    private function getPreviousUserOption(string $resultId, string $optionId): ?UserOption
    {
        $question = $this->optionRepository->getById($optionId)->question;
        $otherOptions = $question->options;
        
        foreach ($otherOptions as $option) {
            $userOption = $this->getUserOption($resultId, $option->id);
            
            if (isset($userOption)) {
                return $userOption;
            }
        }
        
        return null;
    }

    private function checkIsOptionCorrect(string $optionId): bool
    {
        return isset(optional($this->getOptionById($optionId))->answer);
    }

}