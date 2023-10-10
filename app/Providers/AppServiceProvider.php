<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\ActivityService::class, \App\Services\Impl\ActivityServiceImpl::class);
        $this->app->singleton(\App\Services\QuestionService::class, \App\Services\Impl\QuestionServiceImpl::class);
        $this->app->singleton(\App\Services\QuizService::class, \App\Services\Impl\QuizServiceImpl::class);
        $this->app->singleton(\App\Services\QuizSessionService::class, \App\Services\Impl\QuizSessionServiceImpl::class);
        $this->app->singleton(\App\Services\ResultService::class, \App\Services\Impl\ResultServiceImpl::class);
        $this->app->singleton(\App\Services\UserOptionService::class, \App\Services\Impl\UserOptionServiceImpl::class);
        $this->app->singleton(\App\Services\UserService::class, \App\Services\Impl\UserServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
