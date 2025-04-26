<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(){
        //nb de cours inscrit par current user
        $nbcourse=User::withCount('enrolledCourses')->where('users.id', '=',Auth::id())->get();
        //dd($nbcourse);
        //current courses
        $course=Auth::user()->enrolledCourses()->get();
        // $test=Course::withCount(['lessons'=>function($query){
        //     // where('lessons.id','=',);
        // }])->where('users.id','=',Auth::id())->get();
        // dd($test);

        $userId = Auth::id();

// On récupère tous les cours auxquels l'utilisateur est inscrit,le pourcentage de progression
$userId = Auth::id();

$courses = Course::whereHas('students', function($query) use ($userId) {
        $query->where('user_id', $userId);
    })
    ->withCount('lessons')  // Nombre total de leçons par cours
    ->withCount([
        'lessons as completed_lessons_count' => function ($query) use ($userId) {
            $query->whereHas('progresses', function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId)
                         ->where('completed', true);
            });
        }
    ])
    ->get()
    ->map(function($course) {
        // Ajouter le % de progression calculé
        $course->progress_percentage = $course->lessons_count > 0
            ? round(($course->completed_lessons_count / $course->lessons_count) * 100, 2)
            : 0;
        return $course;
    });

   //dd($courses);
   //le nombre de cours fini par l'utilisateur


$enrolledCourses = Course::withCount('lessons')  // nombre total de leçons dans chaque cours
    ->whereHas('students', function ($query) use ($userId) {
        $query->where('user_id', $userId);  // sélectionne les cours où l'utilisateur est inscrit
    })
    ->get();

$completedCourses = $enrolledCourses->filter(function ($course) use ($userId) {
    $completedLessons = $course->lessons()
        ->whereHas('progresses', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('completed', true);
        })->count();

    return $completedLessons === $course->lessons_count;
});

$countCompletedCourses = $completedCourses->count();
// dd($countCompletedCourses);
//average progression

// Total des leçons des cours auxquels il est inscrit
$totalLessons = Lesson::whereHas('course.students', function ($query) use ($userId) {
    $query->where('user_id', $userId);
})->count();

// Total des leçons complétées par l'utilisateur
$completedLessons = Progress::where('user_id', $userId)
    ->where('completed', true)
    ->count();

// Calcul progression %
$averageProgress = $totalLessons > 0 
    ? round(($completedLessons / $totalLessons) * 100, 2)
    : 0;

//dd($averageProgress);

        
        


        return Inertia::render('Dashboard',[
            'countCourse'=>$nbcourse,
             'course'=>$course,
             'courses'=>$courses,
             'countCompletedCourses'=>$countCompletedCourses,
            'averageProgress'=>$averageProgress
        ]);
    }
}
