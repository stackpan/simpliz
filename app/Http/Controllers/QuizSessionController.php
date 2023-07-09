<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\QuizSessionService;

class QuizSessionController extends Controller
{
    
    public function __construct(
        private QuizSessionService $service,
    ) {
    }

    public function start(Request $request): RedirectResponse
    {
        $quizSessionId = $this->service
            ->handleStart($request->quizId);

        return redirect()
            ->route('quiz_sessions.continue', $quizSessionId);
    }

    public function continue(string $id): View
    {
        $quizSession = $this->service->getById($id);

        return view('question.show')
            ->with([
                'quizSession' => $quizSession,
                'questions' => $this->service->getPaginatedQuestions($quizSession)
            ]);
    }

    public function answer(Request $request): RedirectResponse
    {
        $this->service->handleAnswer($request->userOptionId, $request->optionId);

        return redirect()->back();
    }

    public function complete(string $id)
    {
        $quizSession = $this->service->getById($id);

        $this->service->handleComplete($quizSession);
    }
    
}
