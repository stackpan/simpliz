<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionCollection;
use App\Models\Quiz;
use App\Services\QuestionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(private readonly QuestionService $questionService)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request, Quiz $quiz): QuestionCollection
    {
        $this->authorize('update', $quiz);

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $questions = $this->questionService->getPaginatedByQuiz($quiz, $page, $limit);

        return new QuestionCollection($questions);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
