<?php

namespace App\Policies;

use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizSessionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuizSession $quizSession): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $quizId): bool
    {
        return $user->isAssignedTo($quizId);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuizSession $quizSession): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuizSession $quizSession): bool
    {
        //
    }
}
