<?php

namespace App\Services\Impl;

use App\Services\UserService;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Pagination\LengthAwarePaginator;

class UserServiceImpl implements UserService {

    public function getExamineers(int $perPage = 15): LengthAwarePaginator
    {
        return User::select('id', 'name', 'email', 'gender')
            ->whereRole(UserRole::Examinee)
            ->paginate($perPage);
    }

}
