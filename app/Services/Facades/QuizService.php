<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Collection getAll(\App\Models\User|\null $user, \bool $userCount)
 * @method static \App\Models\Quiz loadDetails(\App\Models\Quiz $quiz)
 * @method static \App\Models\Quiz|\null getById(string $id)
 * @method static \string|null create(\string $title, \string $description, \int $duration)
 * @method static \bool update(\App\Models\Quiz $quiz, \string $title, \string $description, \int $duration)
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
