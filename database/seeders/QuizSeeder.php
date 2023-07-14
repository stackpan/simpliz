<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\Answer;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = Quiz::factory()
            ->count(1)
            ->create();

        foreach ($quizzes as $quiz) {
            $questions = Question::factory()
                ->count(12)
                ->for($quiz)
                ->create();

            foreach ($questions as $question) {
                $options = Option::factory()
                    ->count(4)
                    ->for($question)
                    ->create();

                Answer::factory()
                    ->for($options[rand(0, count($options) - 1)])
                    ->create();
            }
        }
    }
}
