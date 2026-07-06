<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'question' => fake()->sentence().'?',
            'type' => fake()->randomElement(['multiple_choice', 'true_false', 'essay']),
            'points' => fake()->randomElement([5, 10, 15, 20]),
            'order' => fake()->numberBetween(1, 10),
            'is_required' => true,
        ];
    }
}
