<?php

namespace Feature\Api;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    private User $participant;

    private Quiz $quiz;

    /**
     * @var Collection<Question>
     */
    private Collection $questions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create();

        $this->participant = User::factory()->participant()->create();

        $this->quiz = Quiz::factory()->for($this->proctor->accountable, 'createdBy')->create();
        $this->quiz->participants()->save($this->participant->accountable);

        $this->questions = Question::factory(30)->for($this->quiz)
            ->has(Option::factory()->count(4))
            ->create();
    }

    public function testGetAllByProctorSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt', 'options'])
                    ->whereType('options', 'array')
                    ->has('options', 4)
                    ->has('options.0', fn(AssertableJson $json) => $json
                        ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
                    )
                )
                ->has('links', fn(AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'currentPage',
                        'from',
                        'lastPage',
                        'path',
                        'perPage',
                        'to',
                        'total',
                        'links',
                    ])
                    ->whereType('links', 'array')
                )
            );
    }

    public function testGetAllByParticipantSuccess(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt', 'options'])
                    ->whereType('options', 'array')
                    ->has('options', 4)
                    ->has('options.0', fn(AssertableJson $json) => $json
                        ->hasAll(['id', 'body', 'createdAt', 'updatedAt'])
                    )
                )
                ->has('links', fn(AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'currentPage',
                        'from',
                        'lastPage',
                        'path',
                        'perPage',
                        'to',
                        'total',
                        'links',
                    ])
                    ->whereType('links', 'array')
                )
            );
    }

    public function testGetAllPagination(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions?page=2&limit=5")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->whereType('data', 'array')
                ->has('data', 5)
                ->has('meta', fn(AssertableJson $json) => $json
                    ->where('currentPage', 2)
                    ->where('lastPage', 6)
                    ->where('perPage', 5)
                    ->etc()
                )
                ->etc()
            );
    }

    public function testGetAllUnauthenticated(): void
    {
        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testGetAllByNonParticipantShouldForbidden(): void
    {
        $user = User::factory()->participant()->create();
        Sanctum::actingAs($user, ['participant']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetAllByNonQuizAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetAllWithQuizNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/quizzes/fictionalquizid/questions')
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Quiz']));
    }

    public function testStoreSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/questions", $payload)
            ->assertCreated()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.resource_created', ['resource' => 'Question']))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt'])
                )
            );

        $this->assertDatabaseHas('questions', [
            'body' => $payload['body'],
            'quiz_id' => $this->quiz->id,
        ]);
    }

    public function testStoreBadPayload(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->post("/api/v2/quizzes/{$this->quiz->id}/questions", [])
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->has('errors', '1')
            );
    }

    public function testStoreUnauthenticated(): void
    {
        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/questions", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testStoreByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/questions", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testStoreByNonQuizAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/questions", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testStoreWithQuizNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post('/api/v2/quizzes/fictionalquizid/questions', $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Quiz']));
    }

    public function testGetByProctorSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->get("/api/v2/questions/{$targetQuestion->id}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.found'))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt', 'options'])
                    ->where('id', $targetQuestion->id)
                    ->whereType('options', 'array')
                    ->has('options.0', fn(AssertableJson $json) => $json
                        ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
                    )
                )
            );
    }

    public function testGetByParticipantSuccess(): void
    {
        Sanctum::actingAs($this->participant, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->get("/api/v2/questions/{$targetQuestion->id}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.found'))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt', 'options'])
                    ->where('id', $targetQuestion->id)
                    ->whereType('options', 'array')
                    ->has('options.0', fn(AssertableJson $json) => $json
                        ->hasAll(['id', 'body', 'createdAt', 'updatedAt'])
                    )
                )
            );
    }

    public function testGetUnauthenticated(): void
    {
        $targetQuestion = $this->questions->random();

        $this->get("/api/v2/questions/{$targetQuestion->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testGetByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->get("/api/v2/questions/{$targetQuestion->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetByNonParticipantShouldForbidden(): void
    {
        $user = User::factory()->participant()->create();
        Sanctum::actingAs($user, ['participant']);

        $targetQuestion = $this->questions->random();

        $this->get("/api/v2/questions/{$targetQuestion->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/questions/fictionalquestionid')
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

    public function testUpdateSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/questions/{$targetQuestion->id}", $payload)
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.resource_updated', ['resource' => 'Question']))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt'])
                    ->where('body', $payload['body'])
                    ->whereNot('updatedAt', $targetQuestion->updated_at)
                )
            );

        $this->assertDatabaseHas('questions', [
            'id' => $targetQuestion->id,
            'body' => $payload['body'],
        ]);
    }

    public function testUpdateBadPayload(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->put("/api/v2/questions/{$targetQuestion->id}", [])
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->has('errors', 1)
            );
    }

    public function testUpdateUnauthenticated(): void
    {
        $targetQuestion = $this->questions->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/questions/{$targetQuestion->id}", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testUpdateByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/questions/{$targetQuestion->id}", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testUpdateByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/questions/{$targetQuestion->id}", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testUpdateNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put('/api/v2/questions/ficitionalquestionid', $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

    public function testDeleteSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->delete("/api/v2/questions/{$targetQuestion->id}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.resource_deleted', ['resource' => 'Question']))
                ->where('data.questionId', $targetQuestion->id)
            );

        $this->assertDatabaseMissing('questions', [
            'id' => $targetQuestion->id,
        ]);
    }

    public function testDeleteUnauthenticated(): void
    {
        $targetQuestion = $this->questions->random();

        $this->delete("/api/v2/questions/{$targetQuestion->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testDeleteByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuestion = $this->questions->random();

        $this->delete("/api/v2/questions/{$targetQuestion->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testDeleteByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['participant']);

        $targetQuestion = $this->questions->random();

        $this->delete("/api/v2/questions/{$targetQuestion->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testDeleteNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->delete('/api/v2/questions/fictionalquestionid')
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

    public function testSetAnswerSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'optionId' => $targetQuestion->options->random()->id,
        ];

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", $payload)
            ->assertOk()
            ->assertJsonPath('message', __('message.answer_set_success'));

        $this->assertDatabaseHas('options', [
            'id' => $payload['optionId'],
            'is_answer' => true,
        ]);
    }

    public function testSetAnswerBadRequest(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", [])
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->whereType('errors', 'array')
                ->has('errors', 1)
            );
    }

    public function testSetAnswerOutsideOfQuestionShouldBadRequest(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();
        $targetOption = $this->questions->filter(fn(Question $question, int $key) => $question !== $targetQuestion)
            ->random()
            ->options->random();

        $payload = [
            'optionId' => $targetOption->id,
        ];

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", $payload)
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->whereType('errors', 'array')
                ->has('errors', 1)
                ->where('errors.optionId.0', __('validation.in_question', [
                    'optionId' => $payload['optionId'],
                    'questionId' => $targetQuestion->id,
                ]))
            );
    }


    public function testSetAnswerUnauthenticated(): void
    {
        $targetQuestion = $this->questions->random();

        $payload = [
            'optionId' => $targetQuestion->options->random()->id,
        ];

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testSetAnswerByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'optionId' => $targetQuestion->options->random()->id,
        ];

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testSetAnswerByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'optionId' => $targetQuestion->options->random()->id,
        ];

        $this->post("/api/v2/questions/{$targetQuestion->id}/set-answer", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testSetAnswerNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuestion = $this->questions->random();

        $payload = [
            'optionId' => $targetQuestion->options->random()->id,
        ];

        $this->post('/api/v2/questions/fictionalquestionid/set-answer', $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

}
