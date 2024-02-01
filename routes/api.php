<?php

use App\Http\Controllers\Api\Authentication\LoginController;
use App\Http\Controllers\Api\Authentication\LogoutController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\QuizParticipantController;
use App\Http\Controllers\Api\SearchParticipantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/v2')->group(function () {
    Route::post('/authentication/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/authentication/logout', LogoutController::class);

        Route::middleware(['ability:proctor'])->group(function () {
            Route::get('/participants', SearchParticipantController::class);
            Route::apiResource('quizzes', QuizController::class)->only(['store', 'update', 'destroy']);
            Route::apiResource('quizzes.participants', QuizParticipantController::class)->only(['index', 'store', 'destroy']);
            Route::apiResource('quizzes.questions', QuestionController::class)->shallow()->only(['store', 'update', 'destroy']);
        });

        Route::apiResource('quizzes', QuizController::class)->except(['store', 'update', 'destroy']);
        Route::apiResource('quizzes.questions', QuestionController::class)->shallow()->except(['store', 'update', 'destroy']);
    });
});

