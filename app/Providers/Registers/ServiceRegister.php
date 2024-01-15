<?php

namespace App\Providers\Registers;

use App\Service\Impl\UserServiceImpl;
use App\Service\UserService;

trait ServiceRegister
{
    public function registerService(): void
    {
        $this->app->singleton(UserService::class, UserServiceImpl::class);
    }
}
