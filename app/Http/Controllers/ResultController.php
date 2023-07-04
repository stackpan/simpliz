<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResultService;

class ResultController extends Controller
{

    public function __construct(
        private ResultService $resultService,
    ) {
        //
    }

    public function storeUserOption(Request $request, string $resultId)
    {
        $this->resultService->saveUserOption($resultId, $request->optionId);
        
        return redirect()->back();
    }
}
