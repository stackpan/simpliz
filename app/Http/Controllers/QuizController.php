<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizCollection;
use App\Services\QuizService;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
