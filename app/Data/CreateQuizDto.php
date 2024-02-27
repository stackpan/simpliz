<?php

namespace App\Data;

class CreateQuizDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $duration,
        public readonly ?string $maxAttempts,
        public readonly string $color,
    )
    {
    }
}
