<?php

namespace App\Repositories\Impl;

use App\Models\Option;
use App\Models\Question;
use App\Repositories\OptionRepository;
use Illuminate\Database\Eloquent\Collection;

class OptionRepositoryImpl implements OptionRepository
{

    public function getAllByQuestion(Question $question): Collection
    {
        return $question->options()->get();
    }

    public function create(Question $question, array $data): Option
    {
        return $question->options()->create($data);
    }

    public function update(Option $option, array $data): Option
    {
        $option->update($data);
        return $option;
    }

    public function delete(Option $option): string
    {
        $option->delete();
        return $option->id;
    }
}
