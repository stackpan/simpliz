<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\Pure;

class UnhashedPasswordException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct('Password is not hashed', 500);
        Log::alert($this->getMessage());
    }

}
