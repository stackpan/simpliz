<?php

namespace App\Repositories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface OptionRepository
{
    /**
     * @return Collection<Option>
     */
    public function getAllByQuestion(Question $question): Collection;

    public function create(Question $question, array $data): Option;

    public function update(Option $option, array $data): Option;

    public function delete(Option $option): string;
}
