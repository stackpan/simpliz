<?php

namespace Tests\Feature\Api;

use App\Enum\Color;
use App\Enum\QuizStatus;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    private User $participant;

    /**
     * @var Collection<Quiz>
     */
    private Collection $quizzes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create([
            'name' => 'proctor',
            'email' => 'proctor@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->participant = User::factory()->participant()->create([
            'name' => 'participant',
            'email' => 'participant@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->quizzes = Quiz::factory(30)->for($this->proctor->accountable, 'createdBy')
            ->sequence(
                ['name' => 'Foo Quiz'],
                ['name' => 'Bar Quiz'],
            )
            ->create();

        $this->participant->accountable->quizzes()->saveMany(
            $this->quizzes->slice(0, 10)
        );
    }

    public function testGetAllByProctor(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/quizzes')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'questionsCount',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                )
                ->has('links', fn (AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn (AssertableJson $json) => $json
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

    public function testGetAllByParticipant(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $this->get('/api/v2/quizzes')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'questionsCount',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                        'attemptCount',
                        'highestScore',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                )
                ->has('links', fn (AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn (AssertableJson $json) => $json
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

        $this->get('/api/v2/quizzes?page=2&limit=5')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 5)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'questionsCount',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                )
                ->has('links', fn (AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn (AssertableJson $json) => $json
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
                    ->where('currentPage', 2)
                    ->where('perPage', 5)
                    ->where('lastPage', 6)
                    ->whereType('links', 'array')
                )
            );
    }

    public function testGetAllSearch(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/quizzes?search=Foo')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'questionsCount',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                )
                ->has('links', fn (AssertableJson $json) => $json
                    ->hasAll(['first', 'last', 'prev', 'next'])
                )
                ->has('meta', fn (AssertableJson $json) => $json
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
                    ->where('currentPage', 1)
                    ->where('perPage', 10)
                    ->where('lastPage', 2)
                    ->where('total', 15)
                    ->whereType('links', 'array')
                )
            );
    }

    public function testGetAllUnauthenticated(): void
    {
        $this->get('/api/v2/quizzes?search=Foo')
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }

    public function testStoreGeneralInformationSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
        ];

        $this->post('/api/v2/quizzes', $payload)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.created'))
                ->has('data', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                )
            );

        $this->assertDatabaseHas('quizzes', [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'duration' => $payload['duration'],
            'max_attempts' => $payload['maxAttempts'],
            'color' => Color::fromName($payload['color']),
            'created_by' => $this->proctor->accountable->id,
        ]);
    }

    public function testStoreGeneralInformationBadPayload(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'description' => fake()->paragraph(),
            'duration' => 0,
            'maxAttempts' => -2,
            'color' => 'Not a color',
        ];

        $this->post('/api/v2/quizzes', $payload)
            ->assertBadRequest()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->whereType('errors', 'array')
                ->has('errors', 4)
            );
    }

    public function testStoreGeneralInformationUnauthenticated(): void
    {
        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
        ];

        $this->post('/api/v2/quizzes', $payload)
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }

    public function testStoreGeneralInformationByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
        ];

        $this->post('/api/v2/quizzes', $payload)
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }

    public function testGetByProctorSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $this->get("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.found'))
                ->has('data', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                    ->where('id', $targetQuiz->id)
                )
            );
    }

    public function testGetByParticipantSuccess(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuiz = $this->participant->accountable->quizzes->random();

        $this->get("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.found'))
                ->has('data', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                        'attemptCount',
                        'highestScore',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                    ->where('id', $targetQuiz->id)
                )
            );
    }

    public function testGetUnauthenticated(): void
    {
        $targetQuiz = $this->quizzes->random();

        $this->get("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc(),
            );
    }

    public function testGetByNonAuthorShouldForbidden(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuiz = Quiz::factory()->create();

        $this->get("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc(),
            );
    }

    public function testGetByNonParticipantShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuiz = Quiz::factory()->create();

        $this->get("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc(),
            );
    }

    public function testGetNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/fictionalid")
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.not_found', ['resource' => 'Quiz']))
                ->etc(),
            );
    }

    public function testUpdateSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->optional()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
            'status' => fake()->randomElement(QuizStatus::getValues()),
        ];

        $this->put("/api/v2/quizzes/{$targetQuiz->id}", $payload)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->has('data', fn (AssertableJson $json) => $json
                    ->hasAll([
                        'id',
                        'name',
                        'description',
                        'duration',
                        'maxAttempts',
                        'color',
                        'status',
                        'createdBy',
                        'createdAt',
                        'updatedAt',
                    ])
                    ->has('createdBy', fn (AssertableJson $json) => $json
                        ->hasAll(['proctorId', 'name'])
                    )
                    ->where('id', $targetQuiz->id)
                    ->where('name', $payload['name'])
                    ->where('description', $payload['description'])
                    ->where('duration', $payload['duration'])
                    ->where('maxAttempts', $payload['maxAttempts'])
                    ->where('color', $payload['color'])
                    ->where('status', $payload['status'])
                )
            );

        $this->assertDatabaseHas('quizzes', [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'duration' => $payload['duration'],
            'max_attempts' => $payload['maxAttempts'],
            'color' => Color::fromName($payload['color']),
            'created_by' => $this->proctor->accountable->id,
        ]);
    }

    public function testUpdateBadRequest(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $payload = [
            'duration' => '1 hour',
            'color' => 'not a color',
            'status' => 'anotherstatus',
        ];

        $this->put("/api/v2/quizzes/{$targetQuiz->id}", $payload)
            ->assertBadRequest()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->has('errors', 4)
            );
    }

    public function testUpdateUnauthenticated(): void
    {
        $targetQuiz = $this->quizzes->random();

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->optional()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
            'status' => fake()->randomElement(QuizStatus::getValues()),
        ];

        $this->put("/api/v2/quizzes/{$targetQuiz->id}", $payload)
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }

    public function testUpdateByNonProctorShouldForbidden(): void
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuiz = $this->quizzes->random();

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->optional()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
            'status' => fake()->randomElement(QuizStatus::getValues()),
        ];

        $this->put("/api/v2/quizzes/{$targetQuiz->id}", $payload)
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }

    public function testUpdateByNonAuthorShouldForbidden(): void
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->optional()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
            'status' => fake()->randomElement(QuizStatus::getValues()),
        ];

        $this->put("/api/v2/quizzes/{$targetQuiz->id}", $payload)
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }

    public function testUpdateNotFound(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $payload = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(1, 100),
            'maxAttempts' => fake()->optional()->numberBetween(1, 5),
            'color' => fake()->randomElement(Color::getNames()),
            'status' => fake()->randomElement(QuizStatus::getValues()),
        ];

        $this->put('/api/v2/quizzes/fictionalid', $payload)
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.not_found', ['resource' => 'Quiz']))
                ->etc()
            );
    }

    public function testDeleteSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $this->delete("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->where('data.quizId', $targetQuiz->id)
            );

        $this->assertDatabaseMissing('quizzes', [
            'id' => $targetQuiz->id,
        ]);
    }

    public function testDeleteUnauthenticated(): void
    {
        $targetQuiz = $this->quizzes->random();

        $this->delete("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }

    public function testDeleteByNonProctorShouldForbidden()
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $targetQuiz = $this->quizzes->random();

        $this->delete("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }

    public function testDeleteByNonAuthorShouldForbidden()
    {
        $user = User::factory()->proctor()->create();
        Sanctum::actingAs($user, ['proctor']);

        $targetQuiz = $this->quizzes->random();

        $this->delete("/api/v2/quizzes/{$targetQuiz->id}")
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }

    public function testDeleteNotFound()
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->delete('/api/v2/quizzes/fictionalid')
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.not_found', ['resource' => 'Quiz']))
                ->etc()
            );
    }

}
