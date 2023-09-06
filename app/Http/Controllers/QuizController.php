<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Services\Impl\QuizServiceImpl;
use Illuminate\View\View;

class QuizController extends Controller
{

    public function __construct(
        private QuizServiceImpl $service,
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
