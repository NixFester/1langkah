<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\ChapterVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChapterVideo>
 */
class ChapterVideoFactory extends Factory
{
    protected $model = ChapterVideo::class;

    public function definition(): array
    {
        return [
            'chapter_id' => Chapter::factory(),
            'title' => 'Video: '.fake()->sentence(4),
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => 'https://picsum.photos/seed/'.fake()->uuid().'/320/180',
            'duration' => fake()->randomElement(['5:00', '10:30', '15:45', '20:00']),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
