<?php

namespace App\Http\Controllers\Manager;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use App\Services\Facades\ActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
}
