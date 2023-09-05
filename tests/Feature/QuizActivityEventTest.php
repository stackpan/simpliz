<?php

namespace Tests\Feature;

use App\Events\QuizActivityEvent;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Utils\Auth as TestUtilAuth;
use Tests\Utils\Seeder as TestUtilSeeder;

class QuizActivityEventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->quiz = Quiz::factory()->create();

        TestUtilSeeder::seedQuizContent($this->quiz);

        $this->user->quizzes()->save($this->quiz);

        TestUtilAuth::userLogin($this, $this->user);

        Event::fake();
    }

    public function test_event_dispatched_at_start_quiz(): void
    {
        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        Event::assertDispatched(QuizActivityEvent::class);
    }

    public function test_event_dispatched_at_answer_quiz(): void
    {
        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();

        $question = $this->quiz->questions()->first();
        $userOption = $result->userOptions()->where('question_id', $question->id)->first();
        $option = $question->options->first();

        $this->patch(route('quiz_sessions.answer'), [
            'userOptionId' => $userOption->id,
            'optionId' => $option->id,
        ]);

        Event::assertDispatched(QuizActivityEvent::class);
    }

    public function test_event_dispatched_at_complete_quiz(): void
    {
        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $this->delete(route('quiz_sessions.complete'));

        Event::assertDispatched(QuizActivityEvent::class);
    }
}
