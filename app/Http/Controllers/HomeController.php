<?php

namespace App\Http\Controllers;

use App\Services\Facades\QuizService;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function index(): View
    {
        return view('home')
            ->with([
                'quizzes' => QuizService::getAll(auth()->user()),
            ]);
    }
}
