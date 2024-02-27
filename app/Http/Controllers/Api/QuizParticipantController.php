<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddQuizParticipantRequest;
use App\Http\Resources\ParticipantCollection;
use App\Models\Participant;
use App\Models\Quiz;
use App\Services\QuizParticipantService;
use App\Services\QuizService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
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

    /**
     * @throws AuthorizationException
     */
    public function store(Quiz $quiz, AddQuizParticipantRequest $request): JsonResponse
    {
        $this->authorize('update', $quiz);

        $validated = $request->validated();

        $this->quizParticipantService->add($quiz, $validated['participantId']);

        return response()->json([
            'message' => __('message.success_attaching', [
                'resourceA' => 'Participant',
                'resourceB' => 'Quiz',
            ]),
            'data' => [
                'quizId' => $quiz->id,
                'participantId' => $validated['participantId'],
            ]
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Quiz $quiz, string $participantId): JsonResponse
    {
        $this->authorize('update', $quiz);

        $this->quizParticipantService->remove($quiz, $participantId);

        return response()->json([
            'message' => __('message.success_detaching', [
                'resourceA' => 'Participant',
                'resourceB' => 'Quiz',
            ]),
            'data' => [
                'quizId' => $quiz->id,
                'participantId' => $participantId,
            ]
        ]);
    }
}
