<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Services\Facades\QuizService;
use Illuminate\View\View;

class QuizController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz): View
    {
        return view('quiz.show')
            ->with([
                'quiz' => QuizService::loadDetails($quiz),
            ]);
    }

}
