<?php

namespace App\Repository\Impl;

use App\Data\RegisterUserData;
use App\Models\User;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserRepositoryImpl implements UserRepository
{

    /**
     * @throws Exception
     */
    public function register(RegisterUserData $data): User
    {
        if (!Hash::isHashed($data->password)) throw new Exception('Password is not hashed');

        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);
    }
}
