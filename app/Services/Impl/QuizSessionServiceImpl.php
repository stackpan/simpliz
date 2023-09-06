<?php

namespace App\Services\Impl;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\UserOption;
use App\Services\QuizSessionService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuizSessionServiceImpl implements QuizSessionService
{

    public function getById(string $id): ?QuizSession
    {
        return QuizSession::find($id);
    }

    public function handleStart(array $validated): QuizSession
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

        cache()->put('quizSessions:' . auth()->user()->id, $quizSession, now()->addMinutes($quiz->duration));

        return $quizSession;
    }

    public function getPaginatedQuestions(QuizSession $quizSession, int $page): LengthAwarePaginator
    {
        return cache()->remember(
            "questions:{$quizSession->result->quiz->id}:$page",
            config('cache.expiration'),
            fn() => $quizSession->result
                ->quiz
                ->questions()
                ->with('options:id,question_id,body')
                ->paginate(1)
        );
    }

    public function handleAnswer(array $validated): void
    {
        DB::transaction(function () use ($validated) {
            extract($validated);

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
                $isAnswerCorrect = optional($userOption)->is_answer;

                $questionsToMany->updateExistingPivot($question->id, [
                    'is_correct' => $isHaveAnswered && $isAnswerCorrect,
                ]);
            });

        $quizSession->result->setCompleted($quizSession->ends_at);
        $quizSession->delete();
        cache()->forget('quizSessions:' . auth()->user()->id);

        DB::commit();

        return $quizSession->result->id;
    }

    public function setLastPage(QuizSession $quizSession, int $page): void
    {
        $quizSession->last_question_page = $page;
        $quizSession->save();
    }
}
