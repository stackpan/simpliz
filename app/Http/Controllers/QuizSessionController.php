<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\QuizSessionService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuizSession\StartQuizSessionRequest;
use App\Http\Requests\QuizSession\AnswerQuizSessionRequest;

class QuizSessionController extends Controller
{
    
    public function __construct(
        private QuizSessionService $service,
    ) {
    }

    public function start(StartQuizSessionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $quizSessionId = $this->service
            ->handleStart($validated);

        return redirect()
            ->route('quiz_sessions.continue', $quizSessionId);
    }

    public function continue(Request $request, string $id): View | RedirectResponse
    {
        $quizSession = $this->service
            ->getById($id);

        if (now()->greaterThan($quizSession->ends_at)) {
            return redirect()->route('quiz_sessions.timeout', $id);
        }

        $pageNumber = $request->query('page');

        if (isset($pageNumber)) {
            $this->service->setLastPage($quizSession, $pageNumber);
        }

        return view('question.show')
            ->with([
                'quizSession' => $quizSession,
                'questions' => $this->service
                    ->getPaginatedQuestions($quizSession)
            ]);
    }

    public function answer(AnswerQuizSessionRequest $request, string $id): RedirectResponse
    {
        $quizSession = $this->service
            ->getById($id);

        if (now()->greaterThan($quizSession->ends_at)) {
            return redirect()->route('quiz_sessions.timeout', $id);
        }

        $validated = $request->validated();

        $this->service
            ->handleAnswer($validated);

        return redirect()->back();
    }

    public function complete(string $id): RedirectResponse
    {
        $quizSession = $this->service
            ->getById($id);

        $resultId = $this->service
            ->handleComplete($quizSession);

        return redirect()
            ->route('results.show', $resultId);
    }

    public function timeout(string $id): View
    {
        return view('question.timeout')
            ->with([
                'quizSessionId' => $id,
            ]);
    }

}
