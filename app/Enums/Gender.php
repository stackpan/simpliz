<?php

namespace App\Enums;

use App\Traits\CanGenerateRandom;

enum Gender: int
{
    use CanGenerateRandom;

    case Male = 0;
    case Female = 1;
}