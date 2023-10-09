<?php

namespace App\Services;

use App\Enums\QuizAction;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ActivityService
{

    public function storeQuizActivity(QuizAction $action, User $user, Quiz $quiz): Activity;

    public function getLatestActivity(int $limit = 10): Collection;

}
