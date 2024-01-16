<?php

namespace App\Data;

class GeneralQuizData implements ValidatedCreatable
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $description,
        public readonly int     $duration,
        public readonly ?int    $maxAttempts,
        public readonly int     $color,
    )
    {
    }

    public static function createFromValidated(array $validated): GeneralQuizData
    {
        return new GeneralQuizData(
            name: $validated['name'],
            description: $validated['description'],
            duration: $validated['duration'],
            maxAttempts: $validated['max_attempts'],
            color: $validated['color'],
        );
    }
}
