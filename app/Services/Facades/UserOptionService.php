<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\UserOption|\null getByForeign(string $resultId, string $questionId)
 *
 * @see \App\Services\UserOptionService
 */
class UserOptionService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\UserOptionService';
    }

}
