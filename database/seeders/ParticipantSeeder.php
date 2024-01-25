<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->participant()->create();
        User::factory()->participant()->create([
            'name' => 'Tester',
            'email' => 'tester@example.com'
        ]);
    }
}
