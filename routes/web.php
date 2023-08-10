<?php

use App\Http\Controllers\{
    HomeController,
    ProfileController,
    QuizController,
    QuizSessionController,
    ResultController,
};
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

    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('quizzes', QuizController::class)->only('show');

    Route::post('/quizzes/work', [QuizSessionController::class, 'start'])->name('quiz_sessions.start');
    Route::get('/quizzes/work/{quizSession}', [QuizSessionController::class, 'continue'])
        ->name('quiz_sessions.continue')
        ->can('view', 'quizSession');
    Route::patch('/quizzes/work/{quizSession}/answer', [QuizSessionController::class, 'answer'])
        ->name('quiz_sessions.answer')
        ->can('update', 'quizSession');
    Route::delete('/quizzes/work/{quizSession}/complete',  [QuizSessionController::class, 'complete'])
        ->name('quiz_sessions.complete')
        ->can('delete', 'quizSession');
    Route::get('/quizzes/work/{quizSession}/timeout', [QuizSessionController::class, 'timeout'])->name('quiz_sessions.timeout');

    Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');
});

require __DIR__.'/auth.php';
