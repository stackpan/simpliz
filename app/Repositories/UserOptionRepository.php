<?php

namespace App\Repositories;

use App\Models\UserOption;

class UserOptionRepository
{
    public function __construct(
        private UserOption $userOption,
    ) {
    }

    public function store(string $resultId, string $optionId): string
    {
        $userOption = new UserOption;

        $userOption->result_id = $resultId;
        $userOption->option_id = $optionId;

        $userOption->save();

        return $userOption->id;
    }

    public function getByResultIdAndOptionId(string $resultId, string $optionId): ?UserOption
    {
        return $this->userOption->where('result_id', $resultId)
            ->where('option_id', $optionId)
            ->first();
    }

    public function updateById(string $id, string $optionId): string
    {
        $userOption = $this->userOption->find($id);

        $userOption->option_id = $optionId;

        $userOption->save();

        return $userOption->id;
    }
}