<?php

namespace App\Providers;

use App\Providers\Registers\RepositoryRegister;
use App\Providers\Registers\ServiceRegister;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use RepositoryRegister, ServiceRegister;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRepository();
        $this->registerService();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
