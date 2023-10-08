<?php

namespace App\Http\Controllers\Manager;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeManagerController extends Controller
{
    public function index(): View
    {
        return view('manager.index')
            ->with([
                'counts' => [
                    'user' => User::whereRole(UserRole::Examinee)->count(),
                    'quiz' => Quiz::count(),
                    'result' => Result::whereNotNull('completed_at')->count(),
                ],
                'activites' => Activity::with('user:id,name')->latest()->get()
            ]);
    }
}
