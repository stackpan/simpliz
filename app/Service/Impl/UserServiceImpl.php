<?php

namespace App\Service\Impl;

use App\Data\RegisterUserData;
use App\Models\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }


    public function register(RegisterUserData $data): User
    {
        $safeData = new RegisterUserData(
            name: $data->name,
            email: $data->email,
            password: Hash::make($data->password),
        );

        return $this->userRepository->register($safeData);
    }
}
