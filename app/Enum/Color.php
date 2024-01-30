<?php

namespace App\Enum;

use App\Traits\HasEnumFromName;
use App\Traits\HasEnumGetNames;

enum Color: int
{
    use HasEnumFromName, HasEnumGetNames;

    case Red = 1;
    case Orange = 2;
    case Yellow = 3;
    case Green = 4;
    case Teal = 5;
    case Blue = 6;
    case Cyan = 7;
    case Purple = 8;
    case Pink = 9;
}
