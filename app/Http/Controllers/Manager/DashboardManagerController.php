<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class DashboardManagerController extends Controller
{

    public function index(): RedirectResponse
    {
        return redirect(route('manager.home.index'));
    }

}
