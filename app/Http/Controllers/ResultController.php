<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\ResultService;

class ResultController extends Controller
{

    public function __construct(
        private ResultService $service,
    ) {
        //
    }

    public function show(string $id): View
    {
        $result = $this->service
            ->getById($id, withDetail: true);
        
        $questions = $this->service
            ->getPaginatedQuestionsResult($result);

        return view('result.show')
            ->with([
                'result' => $result,
                'questions' => $questions,
            ]);
    }
}
