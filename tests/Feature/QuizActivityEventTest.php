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
        $this->post('/quizzes/work', [
            'quizId' => $this->quiz->id,
        ]);

        Event::assertDispatched(QuizActivityEvent::class);
    }

    public function test_event_dispatched_at_answer_quiz(): void
    {
        $this->post('/quizzes/work', [
            'quizId' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();
        $quizSession = $result->quizSession;

        $question = $this->quiz->questions()->first();
        $userOption = $result->userOptions()->where('question_id', $question->id)->first();
        $option = $question->options->first();

        $this->patch('/quizzes/work/' . $quizSession->id . '/answer', [
            'userOptionId' => $userOption->id,
            'optionId' => $option->id,
        ]);

        Event::assertDispatched(QuizActivityEvent::class);
    }

    public function test_event_dispatched_at_complete_quiz(): void
    {
        $this->post('/quizzes/work', [
            'quizId' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();
        $quizSession = $result->quizSession;

        $this->delete('/quizzes/work/' . $quizSession->id . '/complete');

        Event::assertDispatched(QuizActivityEvent::class);
    }
}
