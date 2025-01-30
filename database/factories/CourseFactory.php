<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'price' => (string) $this->faker->randomFloat(2, 10, 100),
            'cover' => 'https://placehold.co/600x400',
            'video_url' => 'https://www.youtube.com/watch?v=6g4O5UOH304',
        ];
    }
}
