<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(4).' Quiz',
            'description' => fake()->optional()->paragraph(),
            'type' => fake()->randomElement(['pre_test', 'post_test']),
            'is_active' => true,
            'passing_score' => fake()->randomElement([60, 70, 80]),
            'time_limit_minutes' => fake()->randomElement([null, 10, 15, 30]),
        ];
    }
}
