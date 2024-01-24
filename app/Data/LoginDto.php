<?php

namespace App\Data;

class LoginDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $password,
    )
    {
    }
}
