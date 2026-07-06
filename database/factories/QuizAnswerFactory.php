<?php

namespace Database\Factories;

use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizAnswer>
 */
class QuizAnswerFactory extends Factory
{
    protected $model = QuizAnswer::class;

    public function definition(): array
    {
        return [
            'question_id' => QuizQuestion::factory(),
            'answer_text' => fake()->sentence(),
            'is_correct' => false,
            'order' => fake()->numberBetween(1, 4),
        ];
    }
}
