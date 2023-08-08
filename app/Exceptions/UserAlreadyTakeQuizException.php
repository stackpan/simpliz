<?php

namespace App\Exceptions;

use Exception;
use App\Models\User;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserAlreadyTakeQuizException extends Exception
{



    public function __construct(
        private User $user,
        private QuizSession $lastQuizSession,
    ) {
        parent::__construct($this->user->name . " is already in a quiz. Complete the last quiz before to start a new one.");
    }

    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [
            'user_id' => $this->user->id,
            'quiz_session_id' => $this->lastQuizSession->id,
        ];
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): RedirectResponse
    {
        return redirect()->back()->withErrors([
            'body' => $this->message,
            'last_session_url' => route('quiz_sessions.continue', $this->lastQuizSession) . '?page=' . $this->lastQuizSession->last_question_page,
        ], 'user_already_take_quiz');
    }

}
