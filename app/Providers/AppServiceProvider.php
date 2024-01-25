<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Repositories\Impl\TokenRepositoryImpl;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Services\AuthenticationService;
use App\Services\Impl\AuthenticationServiceImpl;
use App\Services\Impl\ParticipantServiceImpl;
use App\Services\ParticipantService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TokenRepository::class, TokenRepositoryImpl::class);
        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);

        $this->app->singleton(AuthenticationService::class, AuthenticationServiceImpl::class);
        $this->app->singleton(ParticipantService::class, ParticipantServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
