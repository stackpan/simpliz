<?php

namespace App\Exceptions;

class WrongCredentialsException extends UnauthorizedException
{
    public function __construct(?string $message = null)
    {
        if (!$message) $message = __('message.wrong_credentials');
        parent::__construct($message);
    }
}
