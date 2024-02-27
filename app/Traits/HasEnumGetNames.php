<?php

namespace App\Traits;

trait HasEnumGetNames
{
    /**
     * @return array<string>
     */
    public static function getNames(): array
    {
        return array_column(self::cases(), 'name');
    }
}
