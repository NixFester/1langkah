<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'short_description' => fake()->sentence(10),
            'benefits' => json_encode(['Sertifikat', 'Akses seumur hidup', 'Forum diskusi']),
            'curriculum' => json_encode([]),
            'resources' => json_encode([]),
            'price' => fake()->numberBetween(0, 599000),
            'category' => fake()->randomElement(['Programming', 'Design', 'Marketing', 'Business']),
            'level' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'mentor_id' => null,
            'mentor_name' => fake()->name(),
            'mentor_company' => fake()->company(),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'students_count' => fake()->numberBetween(10, 500),
            'color' => fake()->hexColor(),
            'badge' => fake()->optional()->word(),
        ];
    }
}
