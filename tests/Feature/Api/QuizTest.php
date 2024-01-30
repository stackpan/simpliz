<?php

namespace Tests\Feature\Api;

use App\Enum\Color;
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

    public function testGetByProctorForbidden()
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

    public function testGetByParticipantForbidden()
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

    public function testGetNotFound()
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/fictionalid")
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.not_found', ['resource' => 'Quiz']))
                ->etc(),
            );
    }

}
