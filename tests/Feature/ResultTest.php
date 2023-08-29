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

class ResultTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Quiz $quiz;

    private Result $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->quiz = Quiz::factory()->create();

        TestUtilSeeder::seedQuizContent($this->quiz);

        $this->user->quizzes()->save($this->quiz);

        TestUtilAuth::userLogin($this, $this->user);

        $this->post('/quizzes/work', [
            'quizId' => $this->quiz->id,
        ]);

        $this->result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();
    }

    public function test_user_see_result_success(): void
    {
        $this->delete('/quizzes/work/' . $this->result->quizSession->id . '/complete');

        $response = $this->get('/results/' . $this->result->id);

        $response
            ->assertOk()
            ->assertSeeText($this->quiz->context);
    }

    public function test_user_see_unfinished_quiz_result_should_forbidden(): void
    {
        $response = $this->get('/results/' . $this->result->id);

        $response
            ->assertForbidden();
    }

    public function test_result_only_viewed_by_authorized(): void
    {
        $otherUser = User::factory()->create();

        TestUtilAuth::userLogin($this, $otherUser);

        $this
            ->actingAs($this->user)
            ->delete('/quizzes/work/' . $this->result->quizSession->id . '/complete');

        $response = $this
            ->actingAs($otherUser)
            ->get('/results/' . $this->result->id);

        $response->assertForbidden();
    }
}
