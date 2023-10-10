<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Pagination\LengthAwarePaginator getExamineers(int $perPage = 15)
 *
 * @see \App\Services\UserService
 */
class UserService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\UserService';
    }

}
