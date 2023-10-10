<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserService
{

    public function getExamineers(int $perPage = 15): LengthAwarePaginator;

}
