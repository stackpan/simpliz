<?php

namespace App\Repositories\Impl;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionRepositoryImpl implements QuestionRepository
{
    public function getPaginatedByQuiz(Quiz $quiz, ?int $page = 1, ?int $limit = 10): LengthAwarePaginator
    {
        return $quiz->questions()->with('options')->paginate($limit, page: $page);
    }

    public function create(Quiz $quiz, array $data): Question
    {
        return $quiz->questions()->create($data);
    }

    public function loadDetails(Question $question): Question
    {
        return $question->load('options');
    }

    public function update(Question $question, array $data): Question
    {
        $question->update($data);
        return $question;
    }

    public function delete(Question $question): string
    {
        $question->delete();
        return $question->id;
    }

    public function setAnswer(Question $question, string $optionId): bool
    {
        $question->options()->update(['is_answer' => false]);
        Option::whereId($optionId)->update(['is_answer' => true]);
        return true;
    }

    public function checkOptionExistenceById(Question $question, string $optionId): bool
    {
        return $question->options()->whereId($optionId)->exists();
    }
}
