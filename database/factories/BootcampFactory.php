<?php

namespace Database\Factories;

use App\Models\Bootcamp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bootcamp>
 */
class BootcampFactory extends Factory
{
    protected $model = Bootcamp::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4).' Bootcamp',
            'description' => fake()->paragraph(),
            'short_description' => fake()->sentence(10),
            'type' => fake()->randomElement(['online', 'offline']),
            'participants' => fake()->numberBetween(10, 100),
            'start_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'price' => fake()->numberBetween(500000, 5000000),
            'color' => fake()->hexColor(),
            'sessions_info' => fake()->numberBetween(4, 12).' sessions',
            'jadwal_kelas' => json_encode([
                ['day' => 'Monday', 'time' => '19:00 - 21:00'],
            ]),
            'benefits' => json_encode(['Sertifikat', 'Mentoring 1-on-1', 'Project Portfolio']),
            'icon' => 'laptop',
            'location' => fake()->city(),
            'mentor_id' => null,
            'mentor_name' => fake()->name(),
        ];
    }
}
