<?php

namespace App\Traits;

trait CanGenerateRandom
{

    public static function randomValue()
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }

}