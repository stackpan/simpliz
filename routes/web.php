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
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('quizzes', QuizController::class)->only('show');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/quizzes/work', [QuizSessionController::class, 'start'])->name('quiz_sessions.start');
    Route::get('/quizzes/work/{quiz_session}', [QuizSessionController::class, 'continue'])->name('quiz_sessions.continue');
    Route::patch('/quizzes/work/{quiz_session}/answer', [QuizSessionController::class, 'answer'])->name('quiz_sessions.answer');
    Route::patch('/quizzes/work/{quiz_session}/complete',  [QuizSessionController::class, 'complete'])->name('quiz_sessions.complete');
    Route::get('/quizzes/work/timeout/{quiz_session}', [QuizSessionController::class, 'timeout'])->name('quiz_sessions.timeout');

    Route::get('/results/{result}', [ResultController::class, 'show'])->name('results.show');
});

require __DIR__.'/auth.php';
