<?php

namespace App\Services\Impl;

use App\Data\OptionDto;
use App\Models\Option;
use App\Models\Question;
use App\Repositories\OptionRepository;
use App\Services\OptionService;
use Illuminate\Database\Eloquent\Collection;

class OptionServiceImpl implements OptionService
{
    public function __construct(private readonly OptionRepository $optionRepository)
    {
    }

    public function getAllByQuestion(Question $question): Collection
    {
        return $this->optionRepository->getAllByQuestion($question);
    }

    public function create(Question $question, OptionDto $data): Option
    {
        $attributes = [
            'body' => $data->body,
        ];
        return $this->optionRepository->create($question, $attributes);
    }

    public function update(Option $option, OptionDto $data): Option
    {
        $attributes = [
            'body' => $data->body,
        ];
        return $this->optionRepository->update($option, $attributes);
    }

    public function delete(Option $option): string
    {
        return $this->optionRepository->delete($option);
    }
}
