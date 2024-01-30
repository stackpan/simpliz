<?php

namespace App\Enum;

enum QuizStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Open = 'open';
    case Closed = 'closed';
}
