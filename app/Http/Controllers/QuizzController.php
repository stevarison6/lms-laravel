<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class QuizzController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return Inertia::render('Admin/QuizCreate', [
            'courses' => $courses,
        ]);
}
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);
    
        Quiz::create($validated);
    
        return redirect()->route('quiz.create')->with('success', 'Quiz créé avec succès.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $quiz = Quiz::with('course')->findOrFail($id);
    $questions = $quiz->questions;

    // Trouver le prochain quiz du même cours
    $nextQuiz = Quiz::where('course_id', $quiz->course_id)
        ->where('id', '>', $quiz->id)
        ->orderBy('id')
        ->first();

    return Inertia::render('QuizzPage', [
        'quiz' => $quiz,
        'questions' => $questions,
        'nextQuizId' => $nextQuiz?->id
    ]);
}
    public function manage()
    {
        $quizzes = Quiz::with('course')->latest()->get();

        return Inertia::render('Admin/QuizzManage', [
            'quizzes' => $quizzes,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);

        return Inertia::render('Admin/QuizEdit', [
            'quiz' => $quiz,
            'courses' => Course::select('id', 'title')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);
    
        // Mise à jour du quiz
        $quiz->update([
            'title' => $request->title,
            'course_id' => $request->course_id,
        ]);
    
        return back()->with('success', 'Quizz modifié avec succès');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz): \Illuminate\Http\RedirectResponse
    {
        $quiz->delete();
    
        return back()->with('success', 'Quiz supprimé avec succès.');
    }
    public function submit(Request $request, $id)
{
    $quiz = Quiz::findOrFail($id);
    $user = auth()->user();

    // Valider la structure des réponses
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable|string',
    ]);

    $questions = $quiz->questions;
    $score = 0;
    $total = $questions->count();

    // (Optionnel) Vérifie si le quiz a déjà été soumis par l'utilisateur
    $alreadySubmitted = QuizResult::where('user_id', $user->id)
        ->where('quiz_id', $quiz->id)
        ->exists();

    if ($alreadySubmitted) {
        return response()->json([
            'message' => 'Vous avez déjà soumis ce quiz.'
        ], 403);
    }

    // Comparer chaque réponse utilisateur à la bonne réponse
    foreach ($questions as $question) {
        $userAnswer = $request->input("answers.{$question->id}");

        $options = [
            $question->option_a,
            $question->option_b,
            $question->option_c,
            $question->option_d,
        ];

        if ($userAnswer && in_array($userAnswer, $options)) {
            if ($userAnswer === $question->correct_answer) {
                $score++;
            }
        }
    }

    // Enregistrement du résultat du quiz
    QuizResult::create([
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => $score,
        'completed_at' => now(),
    ]);

    // Retourner les résultats à l'utilisateur
    return response()->json([
        'score' => $score,
        'total' => $total,
        'percentage' => $total > 0 ? round(($score / $total) * 100, 2) : 0,
        'message' => 'Résultats enregistrés avec succès.',
    ]);
}

}
