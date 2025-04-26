<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Inertia\Inertia;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create()
    {
        //return Inertia::render('QuizzQuestion');
        return Inertia::render('Admin/QuestionCreate', [
            'quizzes' => Quiz::select('id', 'title')->get()
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function manage()
    {

         $questions = Question::with('quiz')->get()->map(function ($question) {
        return [
            'id' => $question->id,
            'question_text' => $question->question_text,
            'quiz_title' => $question->quiz ? $question->quiz->title : 'Non défini',
        ];
    });

    return Inertia::render('Admin/QuestionManage', [
        'questions' => $questions,
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);
        //dd($request);
    
        Question::create($validated);
    
        return back()->with('success', 'Question créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   // Édition
public function edit($id)
{
    $question = Question::findOrFail($id);
    return Inertia::render('Admin/QuestionEdit', [
        'question' => $question,
        'quizzes' => Quiz::select('id', 'title')->get(),
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);
    
        $question = Question::findOrFail($id);
        
    
        $question->update([
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'quiz_id' => $request->quiz_id,
        ]);
    
        return back()->with('success', 'Question mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
    
            return back()->with('success', 'Question supprimée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la question');
        }
    }
    
}
