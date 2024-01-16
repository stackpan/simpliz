<?php

namespace App\Data;

class RegisterUserData implements ValidatedCreatable
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    )
    {
    }

    public static function createFromValidated(array $validated): RegisterUserData
    {
        return new RegisterUserData(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password']
        );
    }
}
