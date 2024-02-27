<?php

namespace App\Services\Impl;

use App\Http\Resources\ErrorResponse;
use App\Models\Quiz;
use App\Repositories\QuizParticipantRepository;
use App\Services\QuizParticipantService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizParticipantServiceImpl implements QuizParticipantService
{
    public function __construct(private readonly QuizParticipantRepository $quizParticipantRepository)
    {
    }

    public function checkAuthorization(Quiz $quiz, string $participantId): bool
    {
        return $this->quizParticipantRepository->checkExistenceByParticipantId($quiz, $participantId);
    }

    public function getPaginated(Quiz $quiz, ?string $search, ?int $page, ?int $limit): LengthAwarePaginator
    {
        return $this->quizParticipantRepository->getPaginated($quiz, $search, $page, $limit);
    }

    public function add(Quiz $quiz, string $participantId): bool
    {
        $alreadyExist = $this->quizParticipantRepository->checkExistenceByParticipantId($quiz, $participantId);

        if ($alreadyExist) throw new HttpResponseException(
            (new ErrorResponse([],
                __('message.already_registered', [
                    'resourceA' => 'Participant',
                    'resourceAId' => $participantId,
                    'resourceB' => 'Quiz',
                    'resourceBId' => $quiz->id,
                ])
            ))->response()->setStatusCode(400)
        );

        return $this->quizParticipantRepository->add($quiz, $participantId);
    }

    public function remove(Quiz $quiz, string $participantId): bool
    {
        $alreadyExist = $this->quizParticipantRepository->checkExistenceByParticipantId($quiz, $participantId);

        if (!$alreadyExist) throw new HttpResponseException(
            (new ErrorResponse([],
                __('message.not_found', [
                    'resource' => __('message.registered_resources', [
                        'resourceA' => 'Participant',
                        'resourceAId' => $participantId,
                        'resourceB' => 'Quiz',
                        'resourceBId' => $quiz->id,
                    ])
                ])
            ))->response()->setStatusCode(404)
        );

        return $this->quizParticipantRepository->remove($quiz, $participantId);
    }
}
