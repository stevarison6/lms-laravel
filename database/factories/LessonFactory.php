<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id'=>\App\Models\Course::factory(),
            'title'=>$this->faker->sentence(),
            'content' => implode("\n\n", $this->faker->paragraphs(4)),
            'video_url'=>$this->faker->randomElement(['video-1.mp4', 'video-2.mp4', 'video-3.mp4']),
        ];
    }
}
