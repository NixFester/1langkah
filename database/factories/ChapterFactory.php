<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapter>
 */
class ChapterFactory extends Factory
{
    protected $model = Chapter::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => 'Chapter '.fake()->numberBetween(1, 10).': '.fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'lessons' => fake()->numberBetween(3, 10),
            'order' => fake()->numberBetween(1, 20),
            'duration' => fake()->randomElement(['15m', '30m', '45m', '1h']),
            'video_url' => fake()->optional()->url(),
            'thumbnail_url' => fake()->optional()->imageUrl(320, 180),
        ];
    }
}
