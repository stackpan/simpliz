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
        $quizSessionId = $this->service->handleStart($request->quizId);

        return redirect()->route('quiz_sessions.continue', $quizSessionId);
    }

    public function continue(string $id): View
    {
        $data = $this->service->getQuestionData($id);

        return view('question.show')
            ->with([
                'questions' => $data->get('questions'),
                'quizSession' => $data->get('quizSession'),
            ]);
    }

    public function answer(Request $request, string $id)
    {
        
    }

    public function complete(Request $request, string $id)
    {

    }
    
    public function showQuestions(string $resultId): View
    {
        $result = $this->resultService->getById($resultId);

        return view('question.show')
            ->with([
                'questions' => $this->questionService->getPaginatedByQuizId($result->quiz->id),
                'resultId' => $result->id,
            ]);
    }

    public function finish(string $resultId): RedirectResponse
    {
        $this->resultService->finishResult($resultId);

        return redirect()->route('quizzes.index');
    }
}
