<?php

namespace Tests\Utils;

use App\Models\Quiz;
use App\Models\Option;
use App\Models\Question;

class Seeder {

    public static function seedQuizContent(Quiz $quiz): void
    {
        $questions = Question::factory()
                ->count(2)
                ->for($quiz)
                ->create();

            foreach ($questions as $question) {
                $options = Option::factory()
                    ->count(3)
                    ->for($question)
                    ->create();

                $option = $options[0];
                $option->is_answer = true;
                $option->save();
            }
    }

}
