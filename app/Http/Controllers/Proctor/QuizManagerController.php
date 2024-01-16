<?php

namespace App\Http\Controllers\Proctor;

use App\Data\GeneralQuizData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Proctor\GeneralQuizEditorRequest;
use App\Models\Proctor;
use App\Models\Quiz;
use App\Service\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class QuizManagerController extends Controller
{
    public function __construct(private readonly QuizService $quizService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $proctor = Proctor::find(Auth::id());

        $quizzes = $this->quizService->getAllByAuthor($proctor);

        return Inertia::render('Proctor/Quizzes', [
           'quizzes' => $quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Proctor/GeneralQuizEditor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GeneralQuizEditorRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $data = GeneralQuizData::createFromValidated($validated);
        $proctor = Proctor::find(Auth::id());

        $quiz = $this->quizService->create($data, $proctor);

        return back()->with([
            'quiz' => $quiz
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz): Response
    {
        return Inertia::render('Proctor/GeneralQuizEditor', [
            'quiz' => $quiz
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz): Response
    {
        return Inertia::render('Proctor/GeneralQuizEditor', [
            'quiz' => $quiz
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GeneralQuizEditorRequest $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validated();

        $data = GeneralQuizData::createFromValidated($validated);

        $this->quizService->update($quiz, $data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->quizService->delete($quiz);

        return back();
    }
}
