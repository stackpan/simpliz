<?php

namespace App\Traits;

trait HasEnumFromName
{
    public static function fromName(string $name): mixed
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
