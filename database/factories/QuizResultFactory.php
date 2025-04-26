<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizResult>
 */
class QuizResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>\App\Models\User::factory(),
            'quiz_id'=>\App\Models\Quizz::factory(),
            'score'=>$this->faker->numberBetween(0, 10),
            'completed_at'=>$this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
