<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        $options = [
            fake()->sentence(6),
            fake()->sentence(6),
            fake()->sentence(6),
            fake()->sentence(6),
        ];

        return [
            'content_id' => Content::factory(),
            'question' => fake()->sentence(),
            'options' => $options,
            'correct_answer' => $options[0],
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
