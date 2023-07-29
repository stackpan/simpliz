<?php

namespace App\Policies;

use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizSessionPolicy
{

    public function ownership(User $user, QuizSession $quizSession): bool
    {
        return $quizSession->result->user->is($user);
    }

}
