<?php

namespace Database\Seeders;

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
        $proctor = User::where('name', 'proctor')->first();

        Quiz::factory(15)->for($proctor->accountable, 'createdBy')->create();
    }
}
