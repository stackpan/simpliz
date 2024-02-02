<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Repositories\Impl\QuestionRepositoryImpl;
use App\Repositories\Impl\QuizParticipantRepositoryImpl;
use App\Repositories\Impl\QuizRepositoryImpl;
use App\Repositories\Impl\TokenRepositoryImpl;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizParticipantRepository;
use App\Repositories\QuizRepository;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Services\AuthenticationService;
use App\Services\Impl\AuthenticationServiceImpl;
use App\Services\Impl\ParticipantServiceImpl;
use App\Services\Impl\QuestionServiceImpl;
use App\Services\Impl\QuizParticipantServiceImpl;
use App\Services\Impl\QuizServiceImpl;
use App\Services\ParticipantService;
use App\Services\QuestionService;
use App\Services\QuizParticipantService;
use App\Services\QuizService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{

    public array $singletons = [
        TokenRepository::class => TokenRepositoryImpl::class,
        UserRepository::class => UserRepositoryImpl::class,
        QuizRepository::class => QuizRepositoryImpl::class,
        QuizParticipantRepository::class => QuizParticipantRepositoryImpl::class,
        QuestionRepository::class => QuestionRepositoryImpl::class,
        AuthenticationService::class => AuthenticationServiceImpl::class,
        QuizService::class => QuizServiceImpl::class,
        QuizParticipantService::class => QuizParticipantServiceImpl::class,
        QuestionService::class => QuestionServiceImpl::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
