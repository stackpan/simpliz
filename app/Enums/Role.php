<?php

namespace App\Enums;

use App\Traits\CanGenerateRandom;

enum Role: int
{
    use CanGenerateRandom;

    case Examinee = 0;
    case Admin = 1;
    case SuperAdmin = 2;
}