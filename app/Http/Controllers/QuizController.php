<?php

namespace App\Http\Controllers;

use App\Data\CreateQuizDto;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private readonly QuizService $quizService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): QuizCollection
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $paginatedQuizzes = $this->quizService->getPaginated($request->user(), $search, $page, $limit);
        return new QuizCollection($paginatedQuizzes);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
