<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = Quiz::factory()
            ->count(5)
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

                $answer = $options[rand(0, count($options) - 1)];
                $answer->is_answer = true;
                $answer->save();
            }
        }
    }
}
