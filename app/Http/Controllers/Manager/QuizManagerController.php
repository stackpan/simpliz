<?php

namespace App\Http\Controllers\Manager;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Facades\QuizService;
use App\Http\Requests\Manager\QuizCreateRequest;

class QuizManagerController extends Controller
{
    public function index(): View
    {
        return view('manager.quiz')
            ->with([
                'quizzes' => QuizService::getAll(userCount: true),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizCreateRequest $request)
    {
        $validated = $request->validated();

        $quizId = QuizService::create($validated['title'], $validated['description'], $validated['duration']);

        return redirect()->route('manager.quizzes.edit', $quizId);
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
        $quiz = QuizService::getById($id);


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
