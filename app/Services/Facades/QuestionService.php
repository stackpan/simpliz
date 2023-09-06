<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Pagination\LengthAwarePaginator getPaginatedByResult(\App\Models\Result $result)
 *
 * @see \App\Services\QuestionService
 */
class QuestionService extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'App\Services\QuestionService';
    }

}
