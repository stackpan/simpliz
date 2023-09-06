<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Activity storeQuizActivity(\App\Enums\QuizAction $action, \App\Models\User $user, \App\Models\Quiz $quiz)
 *
 * @see \App\Services\ActivityService
 */
class ActivityService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\ActivityService';
    }

}
