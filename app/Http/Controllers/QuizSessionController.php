<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Services\{QuestionService, ResultService};

class QuizSessionController extends Controller
{
    
    public function __construct(
        private QuestionService $questionService,
        private ResultService $resultService,
    ) {
    }

    public function start(Request $request): RedirectResponse
    {
        $userId = auth()->user()->id;

        $resultId = $this->resultService->store($userId, $request->quizId);

        return redirect()->route('quizzes.sessions.show_questions', ['resultId' => $resultId]);
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
