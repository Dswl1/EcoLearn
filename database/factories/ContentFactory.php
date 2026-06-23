<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'slug' => Str::slug(fake()->sentence()).'-'.Str::random(6),
            'description' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'sdg_category' => 'SDG '.fake()->randomElement(config('sdg.numbers')),
            'tags' => 'test, '.fake()->word(),
            'status' => 'draft',
            'difficulty' => fake()->randomElement(['basic', 'core', 'expert']),
        ];
    }
}
