<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>$this->faker->sentence(),
            'description'=>$this->faker->sentences(3,true),
            'photo_path_course'=>$this->faker->randomElement(['image-1', 'image-2', 'image-3']),
            'instructor_id'=>\App\Models\User::factory(),
        ];
    }
}
