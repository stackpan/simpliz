<?php

namespace Database\Factories;

use App\Enum\Color;
use App\Enum\QuizStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'duration' => fake()->numberBetween(1, 240),
            'max_attempts' => fake()->optional()->numberBetween(1, 10),
            'color' => fake()->randomElement(Color::class),
            'status' => fake()->randomElement(QuizStatus::class),
        ];
    }
}
