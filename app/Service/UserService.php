<?php

namespace App\Service;

use App\Data\RegisterUserData;
use App\Models\User;

interface UserService
{

    public function register(RegisterUserData $data): User;

}
