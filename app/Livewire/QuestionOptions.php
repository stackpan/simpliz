<?php

namespace App\Livewire;

use App\Enums\QuizAction;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\UserOption;
use App\Services\Facades\ActivityService;
use App\Services\Facades\QuizSessionService;
use App\Services\Facades\UserOptionService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class QuestionOptions extends Component
{
    public Collection $options;
    public QuizSession $quizSession;
    public Question $question;

    private UserOption $userOption;

    public function boot()
    {
        $this->userOption = UserOptionService::getByForeign($this->quizSession->result->id, $this->question->id);
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
            $this->quizSession->result->quiz,
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
