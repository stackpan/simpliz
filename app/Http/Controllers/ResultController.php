<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Services\Facades\QuestionService;
use App\Services\Facades\ResultService;
use Illuminate\View\View;

class ResultController extends Controller
{

    public function show(Result $result): View
    {
        return view('result.show')
            ->with([
                'result' => ResultService::loadDetails($result),
                'questions' => QuestionService::getPaginatedByResult($result),
            ]);
    }
}
