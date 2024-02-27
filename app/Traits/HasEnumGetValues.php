<?php

namespace App\Traits;

trait HasEnumGetValues
{
    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
