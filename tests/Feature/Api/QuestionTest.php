<?php

namespace Feature\Api;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private User $proctor;

    private Quiz $quiz;

    /**
     * @var Collection<Question>
     */
    private Collection $questions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->proctor = User::factory()->proctor()->create();

        $this->quiz = Quiz::factory()->for($this->proctor->accountable, 'createdBy')->create();

        $this->questions = Question::factory(30)->for($this->quiz)
            ->has(Option::factory()->count(4))
            ->create();
    }

    public function testGetAllSuccess(): void
    {
        Sanctum::actingAs($this->proctor, ['proctor']);

        $this->get("/api/v2/quizzes/{$this->quiz->id}/questions")
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message', __('message.success'))
                ->whereType('data', 'array')
                ->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->hasAll(['id', 'body', 'createdAt', 'updatedAt', 'options'])
                    ->whereType('options', 'array')
                    ->has('options', 4)
                    ->has('options.0', fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'body', 'isAnswer', 'createdAt', 'updatedAt'])
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


}
