<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<resource>
 */
class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'chapter_id' => null,
            'title' => fake()->word().' Resource',
            'type' => fake()->randomElement(['pdf', 'zip', 'doc', 'video']),
            'url' => 'https://example.com/resources/'.fake()->uuid().'.pdf',
            'file_size' => fake()->numberBetween(100000, 5000000),
            'description' => fake()->optional()->sentence(),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
