<?php

namespace App\Data;

class TokenDto
{
    public function __construct(
        public readonly string $token,
        public readonly string $expiresAt,
        public readonly array $scopes,
    )
    {
    }
}
