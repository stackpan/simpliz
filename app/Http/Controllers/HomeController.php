<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Services\Facades\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function index(): View | RedirectResponse
    {
        if (auth()->user()->role === UserRole::Admin || auth()->user()->role === UserRole::SuperAdmin) {
            return redirect(route('manager.index'));
        }

        return view('home')
            ->with([
                'quizzes' => QuizService::getAll(auth()->user()),
            ]);
    }
}
