<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\QuizSession|\null getById(string $id)
 * @method static \App\Models\QuizSession handleStart(\App\Models\Quiz $quiz)
 * @method static \Illuminate\Pagination\LengthAwarePaginator getPaginatedQuestions(\App\Models\QuizSession $quizSession, int $page)
 * @method static \void handleAnswer(array $validated)
 * @method static \string handleComplete(\App\Models\QuizSession $quizSession)
 * @method static \void setLastPage(\App\Models\QuizSession $quizSession, int $page)
 *
 * @see \App\Services\QuizSessionService
 */
class QuizSessionService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\QuizSessionService';
    }

}
