<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $colors = Arr::map(Color::cases(), fn(UnitEnum $unit) => [
            'id' => $unit->value,
            'name' => $unit->name
        ]);

        DB::table('colors')->insert($colors);
    }
}
