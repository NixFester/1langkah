<?php

namespace Database\Factories;

use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ForumPost>
 */
class ForumPostFactory extends Factory
{
    protected $model = ForumPost::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'student'])->id,
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(2, true),
            'image_urls' => [],
            'upvotes' => fake()->numberBetween(0, 30),
            'downvotes' => fake()->numberBetween(0, 5),
            'reply_count' => fake()->numberBetween(0, 20),
        ];
    }
}
