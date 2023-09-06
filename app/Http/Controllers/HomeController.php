<?php

namespace App\Http\Controllers;

use App\Services\Impl\QuizServiceImpl;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function __construct(
        private QuizServiceImpl $quizService,
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
