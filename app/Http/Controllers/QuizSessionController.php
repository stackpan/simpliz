<?php

namespace App\Http\Controllers;

use App\Enums\QuizAction;
use App\Http\Requests\QuizSession\AnswerQuizSessionRequest;
use App\Http\Requests\QuizSession\StartQuizSessionRequest;
use App\Models\QuizSession;
use App\Services\Facades\ActivityService;
use App\Services\Facades\QuizSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizSessionController extends Controller
{

    public function start(StartQuizSessionRequest $request): RedirectResponse
    {
        $request->ensureUserIsNotInAQuizSession();

        $this->authorize('create', [QuizSession::class, $request->quizId]);

        $quizSession = QuizSessionService::handleStart($request->validated());

        ActivityService::storeQuizActivity(
                QuizAction::Start,
                $request->user(),
                $quizSession->result->quiz
            );

        return redirect()
            ->route('quiz_sessions.continue');
    }

    public function continue(Request $request): View | RedirectResponse
    {
        $quizSession = $request->quizSession;

        $pageNumber = $request->query('page');

        if (isset($pageNumber)) {
            QuizSessionService::setLastPage($quizSession, $pageNumber);
        }

        return view('question.show')
            ->with([
                'quizSession' => $quizSession,
                'questions' => QuizSessionService::getPaginatedQuestions($quizSession, $request->get('page', 1))
            ]);
    }

    public function answer(AnswerQuizSessionRequest $request): RedirectResponse
    {
        $quizSession = $request->quizSession;

        QuizSessionService::handleAnswer($request->validated());

        ActivityService::storeQuizActivity(
                QuizAction::Answer,
                $request->user(),
                $quizSession->result->quiz
            );

        return redirect()->back();
    }

    public function complete(Request $request): RedirectResponse
    {
        $quizSession = $request->quizSession;

        $resultId = QuizSessionService::handleComplete($quizSession);

        ActivityService::storeQuizActivity(
                QuizAction::Complete,
                $request->user(),
                $quizSession->result->quiz
            );

        return redirect()
            ->route('results.show', $resultId);
    }

    public function timeout(): View
    {
        return view('question.timeout');
    }

}
