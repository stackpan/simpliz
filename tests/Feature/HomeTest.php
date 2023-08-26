<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Option;
use Tests\Utils\Seeder as TestUtilSeeder;
use Tests\Utils\Auth as TestUtilAuth;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $quizzes = Quiz::factory()->count(2)->create();
    
        foreach ($quizzes as $quiz) {
            TestUtilSeeder::seedQuizContent($quiz);
        }

        $this->user->quizzes()->save($quizzes[0]);
    }

    public function test_user_can_see_home_page(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $response = $this->get('/');
        $response->assertOk();
    }

    public function test_unauthenticated_must_be_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirectToRoute('login');
    }

    public function test_user_can_only_see_assigned_quizzes(): void
    {
        TestUtilAuth::userLogin($this, $this->user);

        $response = $this->get('/');
        $response
            ->assertOk()
            ->assertViewIs('home');
        
        $this->assertEquals($this->user->quizzes->loadCount('questions')->toArray(), $response['quizzes']->toArray());
    }
}
