<?php

namespace App\Services\Impl;

use App\Enums\QuizAction;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\User;
use App\Services\ActivityService;

class ActivityServiceImpl implements ActivityService
{

    public function storeQuizActivity(QuizAction $action, User $user, Quiz $quiz): Activity
    {
        $body = [
            QuizAction::Start->name => "Start a $quiz->name quiz",
            QuizAction::Answer->name => "Answer a question in $quiz->name quiz",
            QuizAction::Complete->name => "Finish a $quiz->name quiz",
        ];

        return $user->activities()->create([
            'body' => $body[$action->name],
        ]);
    }


}
