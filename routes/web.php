<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\QuizSessionTimeout;
use App\Http\Middleware\QuizSessionMiddleware;
use App\Http\Controllers\QuizSessionController;
use App\Http\Controllers\Manager\HomeManagerController;
use App\Http\Controllers\Manager\QuizManagerController;
use App\Http\Controllers\Manager\UserManagerController;
use App\Http\Controllers\Manager\ResultManagerController;
use App\Http\Controllers\Manager\DashboardManagerController;

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show')->can('view', 'quiz');
    Route::middleware('examinee')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::post('/quiz/sessions', [QuizSessionController::class, 'start'])->name('quiz_sessions.start');
        Route::middleware(QuizSessionMiddleware::class)->group(function () {
            Route::middleware(QuizSessionTimeout::class)->group(function () {
                Route::get('/quiz/sessions', [QuizSessionController::class, 'continue'])->name('quiz_sessions.continue');
                if (config('app.env') === 'testing') Route::patch('/quiz/sessions/answer', [QuizSessionController::class, 'answer'])->name('quiz_sessions.answer');
            });
            Route::delete('/quiz/sessions',  [QuizSessionController::class, 'complete'])->name('quiz_sessions.complete');
            Route::get('/quiz/sessions/timeout', [QuizSessionController::class, 'timeout'])->name('quiz_sessions.timeout');
        });
        Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show')->can('view', 'result');
    });
    Route::middleware('manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/', [DashboardManagerController::class, 'index'])->name('index');
        Route::get('/home', [HomeManagerController::class, 'index'])->name('home.index');
        Route::resource('users', UserManagerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('quizzes', QuizManagerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('/results', [ResultManagerController::class, 'index'])->name('results.index');
    });
});

require __DIR__.'/auth.php';
