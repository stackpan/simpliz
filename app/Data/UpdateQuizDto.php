<?php

namespace App\Data;

class UpdateQuizDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $duration,
        public readonly ?string $maxAttempts,
        public readonly string $color,
        public readonly string $status,
    )
    {
    }
}
