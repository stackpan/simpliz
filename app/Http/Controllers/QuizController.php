<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\QuizService;
use App\Services\ResultService;

class QuizController extends Controller
{

    public function __construct(
        private QuizService $service,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        return view('quiz.show')
            ->with([
                'quiz' => $this->service->getDetail($id),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
