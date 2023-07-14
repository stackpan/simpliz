<?php

namespace App\View\Components;

use Closure;
use App\Models\Quiz;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class QuizInfo extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Quiz $data,
        public ?string $username = null,
        public ?string $score = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.quiz-info')
            ->with([
                'quiz' => $this->data,
                'username' => $this->username,
                'score' => $this->score,
            ]);
    }
}
