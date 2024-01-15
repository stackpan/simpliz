<?php

namespace App\Providers\Registers;

use App\Repository\Impl\UserRepositoryImpl;
use App\Repository\UserRepository;

trait RepositoryRegister
{
    public function registerRepository(): void
    {
        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);
    }
}
