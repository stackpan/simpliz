<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\{QuestionService, ResultService};

class QuizSessionController extends Controller
{
    
    public function __construct(
        private QuestionService $questionService,
        private ResultService $resultService,
    ) {
    }

    public function start(Request $request, string $quizId) {
        $userId = auth()->user()->id;

        $resultId = $this->resultService->store($userId, $quizId);

        return redirect()->route('quiz_session.show_questions', ['id' => $resultId]);
    }
    
    public function showQuestions(string $id)
    {
        $quizId = $this->resultService->getById($id)->quiz->id;

        return view('question.show')->with(
            ['questions' => $this->questionService->getPaginatedByQuizId($quizId)]
        );
    }
}
