<?php

namespace Tests\Feature\Api;

use App\Models\Participant;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuizParticipantTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    private Quiz $quiz;

    /**
     * @var Collection<Participant>
     */
    private Collection $participants;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create([
            'name' => 'proctor',
            'email' => 'proctor@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->quiz = Quiz::factory()->for($this->proctor->accountable, 'createdBy')->create();

        $this->participants = User::factory(30)->participant()
            ->sequence(
                ['first_name' => 'John'],
                ['first_name' => 'Doe'],
            )
            ->create()
            ->map(fn(User $user) => $user->accountable);

        $this->quiz->participants()->saveMany($this->participants);
    }

    public function testGetParticipantsSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn(AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'accountId',
                        'name',
                        'firstName',
                        'lastName',
                        'email',
                        'updatedAt',
                        'createdAt',
                    ])
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

    public function testGetParticipantsPagination(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants?page=2&limit=5")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 5)
                ->where('meta.currentPage', 2)
                ->where('meta.perPage', 5)
                ->where('meta.lastPage', 6)
                ->etc()
            );
    }

    public function testGetParticipantsSearch(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants?search=John")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->where('meta.currentPage', 1)
                ->where('meta.perPage', 10)
                ->where('meta.lastPage', 2)
                ->where('meta.total', 15)
                ->etc()
            );
    }

    public function testGetParticipantsUnauthenticated(): void
    {
        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testGetParticipantsByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participants->random()->account, ['participant']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetParticipantsByNonQuizAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/participants")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testGetParticipantsNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/fictionalquizid/participants?search=John")
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', ['resource' => 'Quiz']));
    }

    public function testAddParticipantToQuizSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = User::factory()->participant()->create()->accountable;

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success_attaching', [
                    'resourceA' => 'Participant',
                    'resourceB' => 'Quiz',
                ]))
                ->where('data.quizId', $this->quiz->id)
                ->where('data.participantId', $targetParticipant->id)
            );

        $this->assertDatabaseHas('participant_quiz', [
            'quiz_id' => $this->quiz->id,
            'participant_id' => $targetParticipant->id,
        ]);
    }

    public function testAddParticipantToQuizBadPayload(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'participantId' => 'not a participant id',
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertBadRequest()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->whereType('errors', 'array')
                ->has('errors', 1)
            );
    }

    public function testAddAlreadyRegisteredParticipantToQuizShouldFail(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = $this->participants->random();

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertBadRequest()
            ->assertJsonPath('message', __('message.already_registered', [
                'resourceA' => 'Participant',
                'resourceAId' => $targetParticipant->id,
                'resourceB' => 'Quiz',
                'resourceBId' => $this->quiz->id,
            ]));
    }

    public function testAddParticipantToQuizUnauthenticated(): void
    {
        $targetParticipant = User::factory()->participant()->create()->accountable;

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testAddParticipantToQuizByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participants->random()->account, ['participant']);

        $targetParticipant = User::factory()->participant()->create()->accountable;

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testAddParticipantToQuizByNonQuizAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetParticipant = User::factory()->participant()->create()->accountable;

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/{$this->quiz->id}/participants", $payload)
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testAddParticipantToQuizNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = User::factory()->participant()->create()->accountable;

        $payload = [
            'participantId' => $targetParticipant->id,
        ];

        $this->post("/api/v2/quizzes/fictionalquizid/participants", $payload)
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', [
                'resource' => 'Quiz',
            ]));
    }

    public function testRemoveParticipantFromQuizSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = $this->participants->random();

        $this->delete("/api/v2/quizzes/{$this->quiz->id}/participants/{$targetParticipant->id}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('message', __('message.success_detaching', [
                    'resourceA' => 'Participant',
                    'resourceB' => 'Quiz',
                ]))
                ->where('data.quizId', $this->quiz->id)
                ->where('data.participantId', $targetParticipant->id)
            );

        $this->assertDatabaseMissing('participant_quiz', [
            'quiz_id' => $this->quiz->id,
            'participant_id' => $targetParticipant->id,
        ]);
    }

    public function testRemoveNotRegisteredParticipantFromQuizShouldFail(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = User::factory()->participant()->create()->accountable;

        $this->delete("/api/v2/quizzes/{$this->quiz->id}/participants/{$targetParticipant->id}")
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', [
                'resource' => __('message.registered_resources', [
                    'resourceA' => 'Participant',
                    'resourceAId' => $targetParticipant->id,
                    'resourceB' => 'Quiz',
                    'resourceBId' => $this->quiz->id,
                ])
            ]));
    }

    public function testRemoveParticipantFromQuizUnauthenticated(): void
    {
        $targetParticipant = $this->participants->random();

        $this->delete("/api/v2/quizzes/{$this->quiz->id}/participants/{$targetParticipant->id}")
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.unauthorized'));
    }

    public function testRemoveParticipantFromQuizByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participants->random()->account, ['participant']);

        $targetParticipant = $this->participants->random();

        $this->delete("/api/v2/quizzes/{$this->quiz->id}/participants/{$targetParticipant->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testRemoveParticipantFromQuizByNonQuizAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetParticipant = $this->participants->random();

        $this->delete("/api/v2/quizzes/{$this->quiz->id}/participants/{$targetParticipant->id}")
            ->assertForbidden()
            ->assertJsonPath('message', __('message.forbidden'));
    }

    public function testRemoveParticipantFromQuizNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetParticipant = $this->participants->random();

        $this->delete("/api/v2/quizzes/fictionalquizid/participants/{$targetParticipant->id}")
            ->assertNotFound()
            ->assertJsonPath('message', __('message.not_found', [
                'resource' => 'Quiz',
            ]));
    }

}
