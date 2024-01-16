<?php

namespace App\Enums;

enum QuizStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Open = 'open';
    case Closed = 'closed';
}
