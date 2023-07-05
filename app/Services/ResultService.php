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

    public function finishResult(string $resultId)
    {
        $userOptions = $this->getById($resultId)->userOptions;

        $userOptions->collect()
            ->each(function (UserOption $userOption, int $key) {
                $isCorrect = $this->checkIsOptionCorrect($userOption->option_id);

                $this->userOptionRepository->patchIsCorrectById($userOption->id, $isCorrect);
            });

        $this->resultRepository->setFinishedById($resultId);
    }

    public function countResultScore(string $resultId): float
    {
        $result = $this->getById($resultId);
        $questionCollections = $result->quiz->questions->collect();
        $userOptionCollection = $result->userOptions->collect();

        $questionsCount = $questionCollections->count();
        $correctOptionCount = $userOptionCollection->filter(function (UserOption $userOption, int $key) {
            return $userOption->is_correct;
        })->count();

        return round(($correctOptionCount / $questionsCount) * 100, 1);
    }

    // private sections

    private function getPreviousUserOption(string $resultId, string $optionId): ?UserOption
    {
        $otherOptions = $this->getOptionById($optionId)
            ->question
            ->options;
        
        foreach ($otherOptions as $option) {
            $userOption = $this->getUserOption($resultId, $option->id);
            
            if (isset($userOption)) {
                return $userOption;
            }
        }
                    
        return null;
    }

    private function getOptionById(string $optionId): ?Option
    {
        return $this->optionRepository->getById($optionId);
    }

    private function checkIsOptionCorrect(string $optionId): bool
    {
        return isset(optional($this->getOptionById($optionId))->answer);
    }

}