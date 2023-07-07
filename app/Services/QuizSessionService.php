<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuizSession;
use App\Models\ResultQuestion;
use Illuminate\Support\Facades\DB;
use App\Repositories\ResultRepository;
use App\Repositories\QuizSessionRepository;
use App\Repositories\ResultQuestionRepository;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSessionService
{

    public function __construct(
        private QuizSessionRepository $quizSessionRepository,
        private ResultRepository $resultRepository,
        private ResultQuestionRepository $resultQuestionRepository,
        private QuizSession $quizSession,
    ) {
        //
    }

    public function handleStart(string $quizId): string
    {
        DB::beginTransaction();

        $result = $this->resultRepository->create(
            userId: auth()->user()->id,
            quizId: $quizId,
        );

        $quizSession = $result->quizSession()->create();
        
        $result->quiz->questions->each(function (Question $question, int $key) use ($result) {

                $resultQuestion = $this->resultQuestionRepository->create(
                    resultId: $result->id,
                    questionId: $question->id
                );

                $resultQuestion->userOption()->create();

            });

        DB::commit();

        return $quizSession->id;
    }

    public function getQuestionData(string $id)
    {
        $quizSession = $this->quizSession->find($id);
        $result = $quizSession->result;

        return collect([
            'quizSession' => $quizSession,
            'questions' => $result->quiz
                ->questionsWithResultData($result)
                ->paginate(1),
        ]);
    }

}