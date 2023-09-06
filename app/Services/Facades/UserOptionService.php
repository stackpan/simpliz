<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class UserOptionService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\UserOptionService';
    }

}
