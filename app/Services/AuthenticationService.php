<?php

namespace App\Services;

use App\Data\LoginDto;
use App\Data\TokenDto;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticationService
{
    public function authenticate(LoginDto $loginDto): TokenDto;

    public function logout(Authenticatable $authenticatable): void;
}
