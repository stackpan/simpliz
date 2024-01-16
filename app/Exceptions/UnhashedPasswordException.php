<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UnhashedPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct('Password is not hashed', 500);
        Log::alert($this->getMessage());
    }

}
