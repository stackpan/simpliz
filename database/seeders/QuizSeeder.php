<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Participant;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proctor = User::whereName('proctor')->first()->accountable;

        $quizzes = Quiz::factory(15)->for($proctor, 'createdBy')->has(
            Question::factory()->count(30)->has(
                Option::factory()->count(4)
            )
        )->create();

        $participants = Participant::all();

        $quizzes->slice(0, 5)->each(function (Quiz $quiz) use ($participants) {
            $quiz->participants()->saveMany(
                $participants->random(3)
            );
        });
    }
}
