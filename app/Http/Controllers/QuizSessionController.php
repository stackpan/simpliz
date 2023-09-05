<?php

namespace App\Http\Controllers;

use App\Enums\QuizAction;
use Illuminate\View\View;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use App\Services\QuizSessionService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuizSession\StartQuizSessionRequest;
use App\Http\Requests\QuizSession\AnswerQuizSessionRequest;

class QuizSessionController extends Controller
{

    public function __construct(
        private QuizSessionService $service,
        private ActivityService $activityService,
    ) {
    }

    public function start(StartQuizSessionRequest $request): RedirectResponse
    {
        $request->ensureUserIsNotInAQuizSession();

        $this->authorize('create', [QuizSession::class, $request->quizId]);

        $quizSession = $this->service
            ->handleStart($request->validated());

        $this->activityService
            ->storeQuizActivity(
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
            $this->service->setLastPage($quizSession, $pageNumber);
        }

        return view('question.show')
            ->with([
                'quizSession' => $quizSession,
                'questions' => $this->service
                    ->getPaginatedQuestions($quizSession)
            ]);
    }

    public function answer(AnswerQuizSessionRequest $request): RedirectResponse
    {
        $quizSession = $request->quizSession;

        $this->service
            ->handleAnswer($request->validated());

        $this->activityService
            ->storeQuizActivity(
                QuizAction::Answer,
                $request->user(),
                $quizSession->result->quiz
            );

        return redirect()->back();
    }

    public function complete(Request $request): RedirectResponse
    {
        $quizSession = $request->quizSession;

        $resultId = $this->service
            ->handleComplete($quizSession);

        $this->activityService
            ->storeQuizActivity(
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
