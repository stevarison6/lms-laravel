<?php

use Inertia\Inertia;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CourController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::middleware([IsAdmin::class])->group(function(){

    Route::get('/create/course',[CourController::class,'create'])->name('course.create');
    Route::post('/create/course',[CourController::class,'store'])->name('course.store');
    Route::get('/manage/course',[CourController::class,'manage'])->name('course.manage');
    Route::delete('/courses/{id}', [CourController::class, 'destroy'])->name('course.destroy');
    Route::post('/lesson', [LessonController::class, 'store'])->name('lesson.store');
    Route::get('/quiz/{id}/edit', [QuizzController::class, 'edit'])->name('quiz.edit');
    Route::delete('/lesson/{id}', [LessonController::class, 'destroy'])->name('lesson.destroy');
    Route::get('manage/lesson', [LessonController::class, 'manage'])->name('lesson.manage');
    Route::get('create/lesson', [LessonController::class, 'create'])->name('lesson.create');
    Route::get('/lessons/{id}/edit', [LessonController::class, 'edit'])->name('lesson.edit');
    Route::put('/lessons/{id}', [LessonController::class, 'update'])->name('lesson.update');
    Route::delete('/quizz/{quiz}', [QuizzController::class, 'destroy'])->name('quiz.destroy');
    Route::get('/manage/quiz',[QuizzController::class,'manage'])->name('quizz.manage');
    Route::put('/quiz/{quiz}', [QuizzController::class, 'update'])->name('quiz.update');
    Route::get('course/{course}/edit', [CourController::class, 'edit'])->name('course.edit');
    Route::get('/admin',[AdminController::class,'index'])->name('admin.page');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::put('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::get('/admin/settings', [AdminController::class, 'edit'])->name('admin.settings.edit');
    Route::get('/create/quiz',[QuizzController::class,'create'])->name('quiz.create');
    Route::post('/create/quizz', [QuizzController::class, 'store'])->name('quiz.store');
    Route::post('/admin/settings/{setting}', [AdminController::class, 'update'])->name('admin.settings.update');
    Route::get('/manage/question',[QuestionController::class,'manage'])->name('question.manage');
    Route::delete('/question/{id}', [QuestionController::class, 'destroy'])->name('question.destroy');
    Route::get('/question/create', [QuestionController::class, 'create'])->name('question.create');
    Route::post('/question', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/question/{id}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    Route::put('/question/{id}', [QuestionController::class, 'update'])->name('question.update');

});
Route::get('/course',[CourController::class,'index'])->name('cours.liste');
Route::get('/cours/{id}',[CourController::class,'show'])->name('cours.show');
Route::get('/subscribe/{courseId}',[CourController::class,'inscrir'])->name('cours.inscription');
Route::get('/lesson/{id}',[LessonController::class,'show'])->name('lesson.show');
Route::post('/lesson/{id}/complete', [LessonController::class, 'markAsCompleted'])->name('lesson.complete');
Route::get('/lesson/{id}/navigate/{direction}', [LessonController::class, 'navigate'])->name('lesson.navigate');
Route::get('/course/{course}/quiz/{quiz}', [QuizzController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{quiz}/submit', [QuizzController::class, 'submit'])->name('quiz.submit');
Route::put('/course/{course}', [CourController::class, 'update'])->name('course.update');
});

