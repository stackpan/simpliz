<?php

namespace Tests\Feature;

use App\Exceptions\UserAlreadyTakeQuizException;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\Result;
use App\Models\User;
use App\Models\UserOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Utils\Seeder as TestUtilSeeder;
use Tests\Utils\Auth as TestUtilAuth;

class QuizSessionTest extends TestCase
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
    }

    public function test_user_start_quiz_success(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $response = $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $response->assertRedirectContains(route('quiz_sessions.start'));

        $this->assertDatabaseHas('results', [
            'user_id' => $this->user->id,
            'quiz_id' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();

        if ($result) {
            $this->assertDatabaseHas('quiz_sessions', [
                'result_id' => $result->id,
            ]);
        }
    }

    public function test_user_cannot_start_disabled_quiz(): void
    {
        $this->quiz->is_enabled = false;
        $this->quiz->save();

        TestUtilAuth::userLogin($this, $this->user);

        $response = $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_start_multiple_quiz(): void
    {
        $otherQuiz = Quiz::factory()->create();
        TestUtilSeeder::seedQuizContent($otherQuiz);
        $this->user->quizzes()->save($otherQuiz);

        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $this->assertThrows(function () use ($otherQuiz) {
            $this->withoutExceptionHandling()
                ->post(route('quiz_sessions.start'), [
                    'quizId' => $otherQuiz->id,
                ]);
        }, UserAlreadyTakeQuizException::class);
    }

    public function test_user_open_question_page()
    {
        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get(route('quiz_sessions.continue'));

        $response
            ->assertOk()
            ->assertSeeText($this->quiz->questions()->first()->context);
    }

    public function test_question_page_opened_by_unauthorized_should_not_found()
    {
        $otherUser = User::factory()->create();

        TestUtilAuth::userLogin($this, $this->user);
        TestUtilAuth::userLogin($this, $otherUser);

        $this
            ->actingAs($this->user)
            ->post(route('quiz_sessions.start'), [
                'quizId' => $this->quiz->id,
            ]);

        $response = $this
            ->actingAs($otherUser)
            ->get(route('quiz_sessions.continue'));

        $response
            ->assertNotFound();
    }

    public function test_user_answering_question_success(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();

        $question = $this->quiz->questions()->first();
        $userOption = $result->userOptions()->where('question_id', $question->id)->first();
        $option = $question->options->first();

        $response = $this->patch(route('quiz_sessions.answer'), [
            'userOptionId' => $userOption->id,
            'optionId' => $option->id,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('user_options', [
            'id' => $userOption->id,
            'option_id' => $option->id,
        ]);
    }

    public function test_user_finish_quiz(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $response = $this->delete(route('quiz_sessions.complete'));

        $response->assertRedirectContains('/results');
    }

    public static function scoreCalculationProvider(): array
    {
        return [
            'partially' => [[2, 1], 50],
            'all wrong' => [[1, 1], 0],
            'all true' => [[2, 2], 100],
        ];
    }

    #[DataProvider('scoreCalculationProvider')]
    public function test_score_calculation(array $selectedOptionIndex, int $expectedScore): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $result = Result::where('user_id', $this->user->id)->where('quiz_id', $this->quiz->id)->first();
        $userOptions = $result->userOptions;

        $this->quiz->questions->each(function (Question $question, int $index) use ($userOptions, $selectedOptionIndex) {
            $options = $question->options;

            $userOption = $userOptions->get($index);
            $userOption->option_id = $options->get($selectedOptionIndex[$index])->id;
            $userOption->save();
        });

        $this->delete(route('quiz_sessions.complete'));

        $result->refresh();

        $this->assertEquals($result->score, $expectedScore);
    }

    public function test_timeout_session_should_redirect(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $this->post(route('quiz_sessions.start'), [
            'quizId' => $this->quiz->id,
        ]);

        $this->travel($this->quiz->duration + 1)->minutes();

        $response = $this->get(route('quiz_sessions.continue'));

        $response->assertRedirectContains(route('quiz_sessions.timeout'));
    }
}
