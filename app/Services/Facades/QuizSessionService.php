<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class QuizSessionService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\QuizSessionService';
    }

}
