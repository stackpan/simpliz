<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $quizzes = Quiz::all();

        $users->each(function (User $user) use ($quizzes) {
            $user->quizzes()->save($quizzes->get(rand(0, $quizzes->count() - 1)));
        });
    }
}
