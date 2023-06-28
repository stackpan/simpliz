<?php

namespace App\Enums;

use App\Traits\CanGenerateRandom;

enum Role: int
{
    use CanGenerateRandom;

    case SuperAdmin = 0;
    case Admin = 1;
    case Examinee = 2;
}