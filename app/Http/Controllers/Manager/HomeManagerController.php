<?php

namespace App\Http\Controllers\Manager;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Enums\UserRole;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Facades\ActivityService;

class HomeManagerController extends Controller
{

    public function index(): View
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
