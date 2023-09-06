<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Collection getAll(\App\Models\User $user)
 * @method static \App\Models\Quiz loadDetails(\App\Models\Quiz $quiz)
 *
 * @see \App\Services\QuizService
 */
class QuizService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\QuizService';
    }

}
