<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class QuizService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\QuizService';
    }

}
