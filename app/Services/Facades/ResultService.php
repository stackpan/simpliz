<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Result loadDetails(\App\Models\Result $result)
 *
 * @see \App\Services\ResultService
 */
class ResultService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\ResultService';
    }

}
