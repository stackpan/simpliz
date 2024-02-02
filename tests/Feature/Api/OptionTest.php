<?php

namespace Tests\Feature\Api;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OptionTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    private User $participant;

    private Quiz $quiz;

    private Question $question;

    /**
     * @var Collection<Option>
     */
    private Collection $options;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create();

        $this->participant = User::factory()->participant()->create();

        $this->quiz = Quiz::factory()->for($this->proctor->accountable, 'createdBy')->create();
        $this->quiz->participants()->save($this->participant->accountable);

        $this->question = Question::factory()->for($this->quiz)->create();

        $this->options = Option::factory(4)->for($this->question)->create();
    }

    public function testGetAllSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/questions/{$this->question->id}/options")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 4)
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
                    ->whereType('isAnswer', 'boolean')
                )
            );
    }

    public function testGetAllByParticipantSuccess(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $this->get("/api/v2/questions/{$this->question->id}/options")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 4)
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt'])
                )
            );
    }

    public function testGetAllUnauthenticated(): void
    {
        $this->get("/api/v2/questions/{$this->question->id}/options")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testGetAllByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $this->get("/api/v2/questions/{$this->question->id}/options")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetAllByNonParticipantShouldForbidden(): void
    {
        $user = User::factory()->participant()->create();
        Sanctum::actingAs($user, ['proctor']);

        $this->get("/api/v2/questions/{$this->question->id}/options")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetAllWithQuestionNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/questions/fictionalquestionid/options')
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

    public function testStoreSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/questions/{$this->question->id}/options", $payload)
            ->assertCreated()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.created'))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
                )
            );

        $this->assertDatabaseHas('options', [
            'question_id' => $this->question->id,
            'body' => $payload['body'],
        ]);
    }

    public function testStoreBadRequest(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->post("/api/v2/questions/{$this->question->id}/options", [])
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->has('errors', 1)
            );
    }

    public function testStoreUnauthenticated(): void
    {
        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/questions/{$this->question->id}/options", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testStoreByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/questions/{$this->question->id}/options", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testStoreByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post("/api/v2/questions/{$this->question->id}/options", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testStoreWithQuestionNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);
        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->post('/api/v2/questions/fictionalquestionid/options', $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Question']));
    }

    public function testUpdateSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetOption = $this->question->options->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/options/{$targetOption->id}", $payload)
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.resource_updated', ['resource' => 'Option']))
                ->has('data', fn(AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
                )
            );

        $this->assertDatabaseHas('options', [
            'id' => $targetOption->id,
            'body' => $payload['body'],
        ]);
    }

    public function testUpdateBadRequest(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetOption = $this->question->options->random();

        $this->put("/api/v2/options/{$targetOption->id}", [])
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->has('errors', 1)
            );
    }

    public function testUpdateUnauthenticated(): void
    {
        $targetOption = $this->question->options->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/options/{$targetOption->id}", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testUpdateByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetOption = $this->question->options->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/options/{$targetOption->id}", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testUpdateByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetOption = $this->question->options->random();

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put("/api/v2/options/{$targetOption->id}", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testUpdateNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'body' => fake()->sentence(),
        ];

        $this->put('/api/v2/options/fictionaloptionid', $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Option']));
    }

    public function testDeleteSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetOption = $this->question->options->random();

        $this->delete("/api/v2/options/{$targetOption->id}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.resource_deleted', ['resource' => 'Option']))
                ->where('data.optionId', $targetOption->id)
            );

        $this->assertDatabaseMissing('options', [
            'id' => $targetOption->id,
        ]);
    }

    public function testDeleteUnauthenticated(): void
    {
        $targetOption = $this->question->options->random();

        $this->delete("/api/v2/options/{$targetOption->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testDeleteByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetOption = $this->question->options->random();

        $this->delete("/api/v2/options/{$targetOption->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testDeleteByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetOption = $this->question->options->random();

        $this->delete("/api/v2/options/{$targetOption->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testDeleteNotFound()
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->delete('/api/v2/options/fictionaloptionid')
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Option']));
    }
}
