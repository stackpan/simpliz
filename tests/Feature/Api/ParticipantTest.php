<?php

namespace Tests\Feature\Api;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create([
            'name' => 'proctor',
            'email' => 'proctor@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function testSearchSuccess(): void
    {
        for ($i = 0; $i < 15; $i++) {
            User::factory()->participant()->create([
                'first_name' => 'Dummy'
            ]);
        }

        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get('/api/v2/participants?search=Dummy')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) => $json
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
                    ->where('firstName', 'Dummy')
                )
            );
    }

    public function testSearchForbidden()
    {
        $participant = User::factory()->participant()->create();

        Sanctum::actingAs($participant, ['participant']);

        $this->get('/api/v2/participants?search=Dummy')
            ->assertForbidden()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.forbidden'))
                ->etc()
            );
    }


}
