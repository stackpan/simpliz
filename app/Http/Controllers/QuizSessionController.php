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

    public function start(Request $request, string $quizId): RedirectResponse
    {
        $userId = auth()->user()->id;

        $resultId = $this->resultService->store($userId, $quizId);

        return redirect()->route('quiz_session.show_questions', ['id' => $resultId]);
    }
    
    public function showQuestions(string $id): View
    {
        $result = $this->resultService->getById($id);

        return view('question.show')
            ->with([
                'questions' => $this->questionService->getPaginatedByQuizId($result->quiz->id),
                'resultId' => $result->id,
            ]);
    }
}
