<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizService;

class QuizController extends Controller
{

    public function __construct(
        private QuizService $quizService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('quiz.index')->with(['quizzes' => $this->quizService->getAll()]);
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
        return view('quiz.show')->with(['quiz' => $this->quizService->getById($id)]);
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

    public function questions(string $id)
    {
        return redirect()->action([
            [QuestionController::class, 'indexPaginate'], ['quiz' => $this->quizService->getById($id)]
        ]);
    }
}
