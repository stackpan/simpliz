<?php

namespace App\Http\Controllers\Manager;

use App\Models\Quiz;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Facades\QuizService;
use App\Http\Requests\Manager\QuizUpdateRequest;

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
    public function store(QuizUpdateRequest $request)
    {
        $validated = $request->validated();

        $quizId = QuizService::create(
            title: $validated['title'],
            description: $validated['description'],
            duration: $validated['duration'],
        );

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
    public function update(QuizUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $quiz = Quiz::find($id);
        $status = QuizService::update(
            quiz: $quiz,
            title: $validated['title'],
            description: $validated['description'],
            duration: $validated['duration']
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
