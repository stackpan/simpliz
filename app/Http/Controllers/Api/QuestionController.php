<?php

namespace App\Http\Controllers\Api;

use App\Data\QuestionDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionCollection;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\QuestionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request, Quiz $quiz): QuestionCollection
    {
        $this->authorize('view', $quiz);

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $questions = $this->questionService->getPaginatedByQuiz($quiz, $page, $limit);

        return new QuestionCollection($questions);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(QuestionRequest $request, Quiz $quiz): JsonResponse
    {
        $this->authorize('update', $quiz);

        $validated = $request->validated();

        $data = new QuestionDto($validated['body']);
        $question = $this->questionService->create($quiz, $data);

        return (new QuestionResource($question))
            ->additional([
                'message' => __('message.resource_created', ['resource' => 'Question']),
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Question $question): QuestionResource
    {
        $this->authorize('view', $question->quiz);

        $questionDetails = $this->questionService->get($question);

        return (new QuestionResource($questionDetails))
            ->additional([
                'message' => __('message.found'),
            ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(QuestionRequest $request, Question $question): QuestionResource
    {
        $this->authorize('update', $question->quiz);

        $validated = $request->validated();

        $data = new QuestionDto($validated['body']);
        $updatedQuestion = $this->questionService->update($question, $data);

        return (new QuestionResource($updatedQuestion))
            ->additional([
                'message' => __('message.resource_updated', ['resource' => 'Question'])
            ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Question $question): JsonResponse
    {
        $this->authorize('update', $question->quiz);

        $questionId = $this->questionService->delete($question);

        return response()->json([
            'message' => __('message.resource_deleted', ['resource' => 'Question']),
            'data' => [
                'questionId' => $questionId,
            ],
        ]);
    }
}
