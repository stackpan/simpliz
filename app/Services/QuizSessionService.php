<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\UserOption;
use App\Models\QuizSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSessionService
{

    public function __construct(
        private QuizSession $quizSession,
    ) {
        //
    }

    public function getById(string $id): ?QuizSession
    {
        return $this->quizSession->find($id);
    }

    public function handleStart(string $quizId): string
    {
        DB::beginTransaction();

        $quiz = Quiz::find($quizId);

        $result = $quiz
            ->results()
            ->create(['user_id' => auth()->user()->id]);

        $result
            ->questions()
            ->attach($quiz->questions);

        DB::commit();

        return $result
            ->quizSession()
            ->create()
                ->id;
    }

    public function getPaginatedQuestions(QuizSession $quizSession): LengthAwarePaginator
    {
        return $quizSession->result
            ->quiz
            ->questions()
            ->paginate(1);
    }

    public function handleAnswer(string $userOptionId, string $optionId): void
    {
        DB::transaction(function () use ($userOptionId, $optionId) {
            $userOption = UserOption::find($userOptionId);
            $userOption->option_id = $optionId;
    
            $userOption->save();
        });
    }

    public function handleComplete(QuizSession $quizSession): string
    {
        DB::beginTransaction();

        $questionsToMany = $quizSession->result->questions();

        $questionsToMany
            ->withPivot('id', 'option_id')
            ->get()
            // process of correcting answers
            ->each(function (Question $question, int $key) use ($questionsToMany) {
                $userOption = $question->pivot->option;

                $isHaveAnswered = $userOption !== null;
                $isAnswerCorrect = optional($userOption)->answer !== null;

                $questionsToMany->updateExistingPivot($question->id, [
                    'is_correct' => $isHaveAnswered && $isAnswerCorrect,
                ]);
            });

        $quizSession->result->setCompleted();
        $quizSession->delete();

        DB::commit();

        return $quizSession->result->id;
    }
}
