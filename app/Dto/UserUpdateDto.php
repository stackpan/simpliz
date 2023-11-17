<?php

namespace App\Dto;

class UserUpdateDto {
    function __construct(
        public string $name,
        public string $email,
        public string $gender,
        public ?string $password,
    ) {}
}
