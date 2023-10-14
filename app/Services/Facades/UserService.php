<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Pagination\LengthAwarePaginator getExamineers(int $perPage = 15)
 * @method static \string create(array $validated, \App\Enums\UserRole $role = \App\Enums\UserRole::Examinee)
 * @method static \bool update(\App\Model\User $user, array $validated)
 * @method static \bool delete(string $userId)
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
