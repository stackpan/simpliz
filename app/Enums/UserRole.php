<?php

namespace App\Enums;

enum UserRole: int
{
    case SuperAdmin = 0;
    case Admin = 1;
    case Examinee = 2;
}