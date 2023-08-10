<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\ResultService;
use App\Services\QuestionService;

class ResultController extends Controller
{

    public function __construct(
        private ResultService $service,
        private QuestionService $questionService,
    ) {
        //
    }

    public function show(Result $result): View
    {   
        return view('result.show')
            ->with([
                'result' => $this->service->loadDetails($result),
                'questions' => $this->questionService->getPaginatedByResult($result),
            ]);
    }
}
