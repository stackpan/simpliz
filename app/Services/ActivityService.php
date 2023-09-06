<?php

namespace App\Services;

use App\Enums\QuizAction;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\User;

interface ActivityService
{

    public function storeQuizActivity(QuizAction $action, User $user, Quiz $quiz): Activity;

}
