<?php

namespace App\Repositories;

use App\Models\Option;

class OptionRepository
{
    public function __construct(
        private Option $option,
    ) {
        //
    }

    public function getById(string $id)
    {
        return $this->option->find($id);
    }
}