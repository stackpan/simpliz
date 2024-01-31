<?php

namespace App\Http\Controllers\Api;

use App\Data\CreateQuizDto;
use App\Data\UpdateQuizDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private readonly QuizService $quizService)
    {
        $this->authorizeResource(Quiz::class, 'quiz');
    }

    public function index(Request $request): QuizCollection
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $paginatedQuizzes = $this->quizService->getPaginated($request->user(), $search, $page, $limit);
        return new QuizCollection($paginatedQuizzes);
    }

    public function store(StoreQuizRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $dto = new CreateQuizDto(
            $validated['name'],
            $validated['description'],
            $validated['duration'],
            $validated['maxAttempts'],
            $validated['color'],
        );
        $proctor = $request->user()->accountable;

        $quiz = $this->quizService->create($dto, $proctor);

        return (new QuizResource($quiz))
            ->additional([
                'message' => __('message.created'),
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Quiz $quiz): QuizResource
    {
        $quiz = $this->quizService->get($quiz, auth()->user());

        return (new QuizResource($quiz))
            ->additional([
                'message' => __('message.found'),
            ]);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): QuizResource
    {
        $validated = $request->validated();

        $dto = new UpdateQuizDto(
            $validated['name'],
            $validated['description'],
            $validated['duration'],
            $validated['maxAttempts'],
            $validated['color'],
            $validated['status'],
        );

        $quiz = $this->quizService->update($quiz, $dto);

        return (new QuizResource($quiz))
            ->additional([
                'message' => __('message.success'),
            ]);
    }

    public function destroy(Quiz $quiz): JsonResponse
    {
        $quizId = $this->quizService->delete($quiz);

        return response()->json([
            'message' => __('message.success'),
            'data' => [
                'quizId' => $quizId,
            ],
        ]);
    }
}
