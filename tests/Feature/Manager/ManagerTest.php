<?php

namespace Tests\Feature\Manager;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utils\Auth as TestUtilAuth;

class ManagerTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthAdminShouldOk()
    {
        $admin = User::factory()->unverified()->testAdminDummy()->create();

        TestUtilAuth::userLogin($this, $admin);

        $response = $this
            ->actingAs($admin)
            ->get(route('manager.home'));

        $response->assertOk();
    }

    public function testAuthSuperAdminShouldOk()
    {
        $superAdmin = $admin = User::factory()->unverified()->superAdmin()->create();

        TestUtilAuth::userLogin($this, $superAdmin);

        $response = $this
            ->actingAs($superAdmin)
            ->get(route('manager.home'));

        $response->assertOk();
    }

    public function testAuthNonAdminShouldForbidden()
    {
        $user = $admin = User::factory()->unverified()->create();

        TestUtilAuth::userLogin($this, $user);

        $response = $this
            ->actingAs($user)
            ->get(route('manager.home'));

        $response->assertForbidden();
    }
}
