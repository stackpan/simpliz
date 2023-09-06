<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\UserOption;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

interface QuizSessionService
{

    public function getById(string $id): ?QuizSession;

    public function handleStart(array $validated): QuizSession;

    public function getPaginatedQuestions(QuizSession $quizSession, int $page): LengthAwarePaginator;

    public function handleAnswer(array $validated): void;

    public function handleComplete(QuizSession $quizSession): string;

    public function setLastPage(QuizSession $quizSession, int $page): void;

}
