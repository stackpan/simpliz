<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private array $credentials;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->credentials = [
            'username' => 'proctor',
            'password' => 'password',
        ];

        $this->user = User::factory()->proctor()->create([
            'name' => $this->credentials['username'],
            'email' => 'proctor@example.com',
            'password' => Hash::make($this->credentials['password']),
        ]);
    }

    public function testLoginSuccess(): void
    {
        $this->post('/api/v2/authentication/login', $this->credentials)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.login_success'))
                ->has('data', fn (AssertableJson $json) => $json
                    ->hasAll(['token', 'expiresAt', 'scopes'])
                    ->whereType('scopes', 'array')
                )
            );
    }

    public function testLoginBadRequest(): void
    {
        $this->post('/api/v2/authentication/login', [
                'username' => $this->credentials['username'],
            ])
            ->assertBadRequest()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.bad_request'))
                ->whereType('errors', 'array')
            );
    }


    public function testLoginWrongCredentials(): void
    {
        $this->post('/api/v2/authentication/login', [
                'username' => $this->credentials['username'],
                'password' => 'wrongpassword',
            ])
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.wrong_credentials'))
                ->etc()
            );
    }

    public function testLogoutSuccess(): void
    {
        Sanctum::actingAs($this->user, ['proctor']);

        $this->delete('/api/v2/authentication/logout')
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.logout_success'))
            );
    }

    public function testLogoutUnauthorized(): void
    {
        $this->delete('/api/v2/authentication/logout')
            ->assertUnauthorized()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.unauthorized'))
                ->etc()
            );
    }

    public function testAntiAuthenticationTokenDuplication(): void
    {
        $this->post('/api/v2/authentication/login', $this->credentials);
        $this->post('/api/v2/authentication/login', $this->credentials);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }


}
