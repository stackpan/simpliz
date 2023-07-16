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
        private QuizSession $model,
    ) {
        //
    }

    public function getById(string $id): ?QuizSession
    {
        return $this->model->find($id);
    }

    public function handleStart(array $validated): string
    {
        extract($validated);

        DB::beginTransaction();

        $quiz = Quiz::find($quizId);

        $result = $quiz
            ->results()
            ->create(['user_id' => auth()->user()->id]);

        $result
            ->questions()
            ->attach($quiz->questions);

        $quizSession = $result
            ->quizSession()
            ->create([
                'ends_at' => now()->addMinutes($result->quiz->duration),
            ]);

        DB::commit();

        return $quizSession->id;
    }

    public function getPaginatedQuestions(QuizSession $quizSession): LengthAwarePaginator
    {
        return $quizSession->result
            ->quiz
            ->questions()
            ->paginate(1);
    }

    public function handleAnswer(array $validated): void
    {
        extract($validated);

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
