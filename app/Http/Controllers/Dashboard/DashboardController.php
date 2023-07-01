<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\Role;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == Role::SuperAdmin->value) {
            return redirect()->route('dashboard.admin');
        }

        return redirect()->route('dashboard.home');
    }

    public function home()
    {
        return view('dashboard.home.index');
    }

    public function user()
    {
        return view('dashboard.user.index');
    }

    public function quiz()
    {
        return view('dashboard.quiz.index');
    }

    public function result()
    {
        return view('dashboard.result.index');
    }
}
