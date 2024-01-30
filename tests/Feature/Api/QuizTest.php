<?php

namespace Tests\Feature\Api;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $this->quizzes = Quiz::factory(20)->for($this->proctor->accountable, 'createdBy')->create([
            'name' => 'Test Quiz',
        ]);

        $this->participant->accountable->quizzes()->saveMany(
            $this->quizzes->slice(0, 10)
        );
    }

    public function testGetAllByProctor()
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/quizzes?limit=5')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', 'Success.')
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
                    ->where('currentPage', 1)
                    ->where('perPage', 5)
                    ->whereType('links', 'array')
                )
            );
    }

    public function testGetAllByParticipant()
    {
        Sanctum::actingAs($this->participant, ['participant']);

        $this->get('/api/v2/quizzes?page=1&limit=5')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', 'Success.')
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
                    ->where('currentPage', 1)
                    ->where('perPage', 5)
                    ->whereType('links', 'array')
                )
            );
    }

}
