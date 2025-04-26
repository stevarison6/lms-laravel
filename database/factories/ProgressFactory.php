<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Progress>
 */
class ProgressFactory extends Factory
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
            'lesson_id'=>\App\Models\Lesson::factory(),
            'completed'=>$this->faker->randomElement(['true','false']),
            'completed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),//les lessons finis
        ];
    }
}
