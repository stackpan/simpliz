<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetAnswerQuestionRequest;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SetAnswerQuestionController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function __invoke(SetAnswerQuestionRequest $request, Question $question): JsonResponse
    {
        $this->authorize('update', $question->quiz);

        $validated = $request->validated();
        $this->questionService->setAnswer($question, $validated['optionId']);

        return response()->json([
            'message' => __('message.answer_set_success'),
        ]);
    }
}
