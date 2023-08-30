<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
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
    public function show(Quiz $quiz): View
    {
        return view('quiz.show')
            ->with([
                'quiz' => $this->service->loadDetails($quiz),
            ]);
    }

}
