<?php

use App\Http\Controllers\{
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('quizzes', QuizController::class)
    ->only('index', 'show');

Route::post('/quiz-sessions', [QuizSessionController::class, 'start'])->name('quiz_sessions.start');
Route::get('/quiz-sessions/{quiz_session}', [QuizSessionController::class, 'continue'])->name('quiz_sessions.continue');
Route::post('/quiz-sessions/{quiz_session}/answer', [QuizSessionController::class, 'answer'])->name('quiz_sessions.answer');
Route::patch('/quiz-sessions/{quiz_session}/complete',  [QuizSessionController::class, 'complete'])->name('quiz_sessions.complete');

Route::post('/results/{id}/options', [ResultController::class, 'storeUserOption'])->name('results.store_user_option');

require __DIR__.'/auth.php';
