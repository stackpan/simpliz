<?php

namespace App\Dto;

class QuizUpdateDto {
    function __construct(
        public string $title,
        public string $description,
        public int $duration,
    ) {}
}

