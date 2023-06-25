<?php

namespace App\Enums;

enum Role: int
{
    case Examinee = 0;
    case Admin = 1;
    case SuperAdmin = 2;
}