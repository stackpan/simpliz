<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\QuizService;
use App\Services\ResultService;

class QuizController extends Controller
{

    public function __construct(
        private QuizService $service,
    ) {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $quiz = $this->service->getDetail($id);

        $this->authorize('view', $quiz);

        return view('quiz.show')
            ->with([
                'quiz' => $quiz,
            ]);
    }
    
}
