<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Activity;
use App\Enums\QuizAction;

class ActivityService
{

    public function __construct(
        private Activity $model,
    ) {
        //
    }

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