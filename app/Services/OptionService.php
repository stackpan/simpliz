<?php

namespace App\Services;

use App\Data\OptionDto;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface OptionService
{
    /**
     * @return Collection<Option>
     */
    public function getAllByQuestion(Question $question): Collection;

    public function create(Question $question, OptionDto $data): Option;

    public function update(Option $option, OptionDto $data): Option;

    public function delete(Option $option): string;
}
