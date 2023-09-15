<?php

namespace App\Livewire;

use App\Enums\QuizAction;
use App\Models\UserOption;
use App\Services\Facades\ActivityService;
use App\Services\Facades\QuizSessionService;
use App\Services\Facades\UserOptionService;
use Livewire\Component;

class QuestionOptions extends Component
{
    public array $options;
    public array $quizSession;
    public string $questionId;

    private UserOption $userOption;

    public function boot()
    {
        $this->userOption = UserOptionService::getByForeign($this->quizSession['result']['id'], $this->questionId);
    }

    public function setAnswer(string $optionId)
    {
        QuizSessionService::handleAnswer([
            'userOptionId' => $this->userOption->id,
            'optionId' => $optionId,
        ]);

        ActivityService::storeQuizActivity(
            QuizAction::Answer,
            auth()->user(),
            $this->quizSession['result']['quiz'],
        );
    }

    public function render()
    {
        return view('livewire.question-options')
            ->with([
                'userOption' => $this->userOption,
            ]);
    }
}
