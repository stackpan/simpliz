<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\QuizService;

class HomeController extends Controller
{

    public function __construct(
        private QuizService $quizService,
    ) {
        //
    }

    public function index(): View
    {
        return view('home')
            ->with([
                'quizzes' => $this->quizService->getAll(auth()->user()),
            ]);
    }
}
