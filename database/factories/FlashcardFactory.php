<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashcardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content_id' => Content::factory(),
            'question' => fake()->sentence(),
            'answer' => fake()->paragraph(),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
