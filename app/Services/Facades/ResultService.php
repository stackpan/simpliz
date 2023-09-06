<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class ResultService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\ResultService';
    }

}
