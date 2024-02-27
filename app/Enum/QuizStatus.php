<?php

namespace App\Enum;

use App\Traits\HasEnumGetValues;

enum QuizStatus: string
{
    use HasEnumGetValues;

    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
}
