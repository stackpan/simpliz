<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/quizzes/{quiz}', [\App\Http\Controllers\QuizController::class, 'show'])
        ->name('quizzes.show')
        ->can('view', 'quiz');

    Route::middleware('examinee')->group(function () {

        Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])
            ->name('home');

        Route::post('/quiz/sessions', [\App\Http\Controllers\QuizSessionController::class, 'start'])
            ->name('quiz_sessions.start');

        Route::middleware(\App\Http\Middleware\QuizSessionMiddleware::class)->group(function () {

            Route::middleware(\App\Http\Middleware\QuizSessionTimeout::class)->group(function () {

                Route::get('/quiz/sessions', [\App\Http\Controllers\QuizSessionController::class, 'continue'])
                    ->name('quiz_sessions.continue');

                if (config('app.env') === 'testing') {
                    Route::patch('/quiz/sessions/answer', [\App\Http\Controllers\QuizSessionController::class, 'answer'])
                        ->name('quiz_sessions.answer');
                }

            });

            Route::delete('/quiz/sessions',  [\App\Http\Controllers\QuizSessionController::class, 'complete'])
                ->name('quiz_sessions.complete');

            Route::get('/quiz/sessions/timeout', [\App\Http\Controllers\QuizSessionController::class, 'timeout'])
                ->name('quiz_sessions.timeout');
        });

        Route::get('/results/{result}', [\App\Http\Controllers\ResultController::class, 'show'])
            ->name('results.show')
            ->can('view', 'result');

    });

    Route::middleware('manager')->group(function () {

        Route::get('/manager', [\App\Http\Controllers\Manager\DashboardController::class, 'index'])
            ->name('manager.index');

        Route::get('/manager/home', [\App\Http\Controllers\Manager\DashboardController::class, 'home'])
            ->name('manager.home');

        Route::get('/manager/user', [\App\Http\Controllers\Manager\DashboardController::class, 'user'])
            ->name('manager.user');

        Route::get('/manager/user/create', [\App\Http\Controllers\Manager\UserEditorController::class, 'create'])
            ->name('manager.user.create');

        Route::post('/manager/users', [\App\Http\Controllers\Manager\UserEditorController::class, 'store'])
            ->name('manager.user.store');

        Route::get('/manager/user/{user}/edit', [\App\Http\Controllers\Manager\UserEditorController::class, 'edit'])
            ->name('manager.user.edit');

        Route::put('/manager/users/{user}', [\App\Http\Controllers\Manager\UserEditorController::class, 'update'])
            ->name('manager.user.update');

        Route::get('/manager/quiz', [\App\Http\Controllers\Manager\DashboardController::class, 'quiz'])
            ->name('manager.quiz');

        Route::get('/manager/result', [\App\Http\Controllers\Manager\DashboardController::class, 'result'])
            ->name('manager.result');

    });
});

require __DIR__.'/auth.php';
