<?php

namespace App\Rules;

use App\Models\Question;
use App\Services\QuestionService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InQuestion implements ValidationRule
{
    private readonly QuestionService $questionService;

    public function __construct(
        private readonly Question $question
    )
    {
        $this->questionService = app(QuestionService::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exist = $this->questionService->checkOptionExistence($this->question, $value);
        if (!$exist)
            $fail('validation.in_question')->translate([
                'optionId' => $value,
                'questionId' => $this->question->id,
            ]);
    }
}
