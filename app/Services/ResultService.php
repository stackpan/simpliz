<?php

namespace App\Services;

use App\Models\Result;

interface ResultService
{

    public function loadDetails(Result $result): Result;

}
