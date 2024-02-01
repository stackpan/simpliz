<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParticipantCollection;
use App\Models\Participant;
use App\Models\Quiz;
use App\Services\QuizParticipantService;
use App\Services\QuizService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class QuizParticipantController extends Controller
{
    public function __construct(private readonly QuizParticipantService $quizParticipantService)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Quiz $quiz, Request $request): ParticipantCollection
    {
        $this->authorize('view', $quiz);

        $search = $request->query('search');
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $participants = $this->quizParticipantService->getPaginated($quiz, $search, $page, $limit);

        return new ParticipantCollection($participants);
    }

    public function store(Quiz $quiz, Request $request)
    {

    }

    public function destroy(Quiz $quiz, Participant $participant)
    {
        //
    }
}
