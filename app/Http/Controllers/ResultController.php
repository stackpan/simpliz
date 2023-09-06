<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Services\Impl\QuestionServiceImpl;
use App\Services\Impl\ResultServiceImpl;
use Illuminate\View\View;

class ResultController extends Controller
{

    public function __construct(
        private ResultServiceImpl   $service,
        private QuestionServiceImpl $questionService,
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
