<?php

namespace App\Services;

use App\Data\LoginDto;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticationService
{
    public function authenticate(LoginDto $loginDto): string;

    public function logout(Authenticatable $authenticatable): void;
}
