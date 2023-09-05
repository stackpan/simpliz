<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utils\Auth as TestUtilAuth;
use Tests\Utils\Seeder as TestUtilSeeder;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Quiz $quiz;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->quiz = Quiz::factory()->create();

        TestUtilSeeder::seedQuizContent($this->quiz);

        $this->user->quizzes()->save($this->quiz);

        TestUtilAuth::userLogin($this, $this->user);
    }

    public function test_start_quiz_activity_creation(): void
    {
        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $this->user->id,
            'body' => 'Start a ' . $this->quiz->name . ' quiz',
        ]);
    }

    public function test_answer_quiz_activity_creation(): void
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

        $this->assertDatabaseHas('activities', [
            'user_id' => $this->user->id,
            'body' => 'Answer a question in ' . $this->quiz->name . ' quiz',
        ]);
    }

    public function test_complete_quiz_activity_creation(): void
    {
        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $this->delete(route('quiz_sessions.complete'));

        $this->assertDatabaseHas('activities', [
            'user_id' => $this->user->id,
            'body' => 'Finish a ' . $this->quiz->name . ' quiz',
        ]);
    }
}
