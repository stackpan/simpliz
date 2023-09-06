<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\ActivityService';
    }

}
