<?php

namespace Tests\Feature\Api;

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

    public function testGetAllByProctor()
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

    public function testGetAllByParticipant()
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

    public function testGetAllPagination()
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

    public function testGetAllSearch()
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

    public function testGetAllUnauthenticated()
    {
        $this->get('/api/v2/quizzes?search=Foo')
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }


}
