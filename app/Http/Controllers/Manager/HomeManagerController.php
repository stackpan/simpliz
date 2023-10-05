<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeManagerController extends Controller
{
    public function index(): View
    {
        return view('manager.index');
    }
}
