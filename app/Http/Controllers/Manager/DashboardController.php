<?php

namespace App\Http\Controllers\Manager;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Enums\UserRole;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Services\Facades\UserService;
use Illuminate\Http\RedirectResponse;
use App\Services\Facades\ActivityService;

class DashboardController extends Controller
{

    public function index(): RedirectResponse
    {
        return redirect(route('manager.home'));
    }

    public function home(): View
    {
        return view('manager.home')
            ->with([
                'counts' => [
                    'user' => User::whereRole(UserRole::Examinee)->count(),
                    'quiz' => Quiz::count(),
                    'result' => Result::whereNotNull('completed_at')->count(),
                ],
                'activites' => ActivityService::getLatestActivity()
            ]);
    }

    public function user(): View
    {
        return view('manager.user')
            ->with([
                'users' => UserService::getExamineers(),
            ]);
    }

    public function quiz(): View
    {
        return view('manager.quiz');
    }

    public function result(): View
    {
        return view('manager.result');
    }
}
