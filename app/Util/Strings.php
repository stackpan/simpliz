<?php

namespace App\Util;

use Illuminate\Support\Str;

class Strings
{
    public static function shortenClassName(string $className): string
    {
        return ltrim(preg_replace('/[A-Z]/', ' $0', class_basename($className)));
    }
}
