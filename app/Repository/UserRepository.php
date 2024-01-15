<?php

namespace App\Repository;

use App\Data\RegisterUserData;
use App\Models\User;

interface UserRepository
{
    public function register(RegisterUserData $data): User;
}
