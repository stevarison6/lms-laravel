<?php

namespace Database\Seeders;

use App\Models\Quiz;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {  
        //  $users = User::factory()->count(30)->create();  // creation 30 utilisateur
        // // User::factory(10)->create();
        // \App\Models\Course::factory()->count(50)->create()->each(function ($course) use ($users) {
        //     // inscrir 1 à 12 utilisateur à chaque course
        //     $course->students()->attach(
        //         $users->random(rand(1, 12))->pluck('id')->toArray()
        //     );
        // });

        // // Récupérer tous les cours existants
        // $courses = \App\Models\Course::all(); 
        //  // Pour chaque cours, créer 10 leçons
        // $courses->each(function ($course) {
        // \App\Models\Lesson::factory()->count(10)->create([
        //     'course_id' => $course->id
        // ]);
        // });

        // Récupérer tous les courses existants
        // $courses = \App\Models\Course::all();

        // $courses->each(function ($course) {
        // // Créer un quiz lié au cours
        // $quiz = \App\Models\Quiz::factory()->create([
        //     'course_id' => $course->id
        // ]);

        // // Créer 10 questions liées au quiz
        // \App\Models\Question::factory()->count(10)->create([
        //     'quiz_id' => $quiz->id
        // ]);
    //});
     // S'assurer qu'on a 10 cours
     if (Course::count() < 10) {
        Course::factory(10)->create();
    }

    $courses = Course::take(10)->get();

    // Créer les 10 quizzes
    $quizzes = collect();
    foreach ($courses as $course) {
        $quizzes->push(
            Quiz::create([
                'title' => 'Quiz sur ' . $course->title,
                'course_id' => $course->id,
            ])
        );
    }

    // Ajouter des questions à 5 quizz aléatoires
    $quizzes->random(5)->each(function ($quiz) {
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question $i du quiz {$quiz->title}",
                'option_a' => "Option A",
                'option_b' => "Option B",
                'option_c' => "Option C",
                'option_d' => "Option D",
                'correct_answer' => "A",
            ]);
        }
    });




    }
}
