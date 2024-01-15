<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Arrays;
use UnitEnum;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $colors = Arrays::map(Color::cases(), fn(UnitEnum $unit) => [
            'id' => $unit->value,
            'name' => $unit->name
        ]);

        DB::table('colors')->insert($colors);
    }
}
