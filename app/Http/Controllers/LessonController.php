<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::select('id', 'title')->get();

        return Inertia::render('Admin/LessonCreate', [
            'courses' => $courses
        ]);;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'video_url' => 'required|file|mimes:mp4,mov,avi,webm|max:204800',
        ]);
    
        $videoName = null;
        if ($request->hasFile('video_url')) {
            $videoName = time() . '_' . $request->file('video_url')->getClientOriginalName();
            $request->file('video_url')->storeAs('public/Video', $videoName);
        }
    
        Lesson::create([
            'title' => $request->title,
            'content' => $request->content,
            'course_id' => $request->course_id,
            'video_url' => $videoName,
        ]);
    
        return redirect()->back()->with('success', 'Leçon créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $lesson = Lesson::with('course')->findOrFail($id);
        
        $progress = Progress::where('user_id', $user->id)
                            ->where('lesson_id', $lesson->id)
                            ->first();

        return Inertia::render('LessonContent', [
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'videoUrl' =>asset('storage/Video/'.$lesson->video_url) ,
                'content' => explode("\n\n", $lesson->content),  // inverse de implode();il faut stocker les donnees avec implode pour ne pas avoir d'erreur
                'completed' => $progress ? $progress->completed : false,
                'course_id'=>$lesson->course_id,
            ],

        ]);
    }

    public function markAsCompleted(Request $request, $id)
    {
        $user = $request->user();

        Progress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $id],
            ['completed' => true, 'completed_at' => now()]
        );

        return back()->with('success', 'Leçon marquée comme terminée !');
    }

    public function navigate(Request $request, $id, $direction)
    {
        $currentLesson = Lesson::findOrFail($id);

        $nextLesson = null;

        if ($direction === 'next') {
            $nextLesson = Lesson::where('course_id', $currentLesson->course_id)
                ->where('id', '>', $currentLesson->id)
                ->orderBy('id')
                ->first();
        } elseif ($direction === 'prev') {
            $nextLesson = Lesson::where('course_id', $currentLesson->course_id)
                ->where('id', '<', $currentLesson->id)
                ->orderByDesc('id')
                ->first();
        }

        if ($nextLesson) {
            return redirect()->route('lesson.show', $nextLesson->id);
        }

        return back()->with('error', 'Aucune autre leçon disponible.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lesson = Lesson::findOrFail($id);
        $courses = Course::all(['id', 'title']);
    
        return Inertia::render('Admin/LessonEdit', [
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'course_id' => $lesson->course_id,
            ],
            'courses' => $courses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'content' => 'nullable|string',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200',
        ]);
    
        $lesson->title = $validated['title'];
        $lesson->content = $validated['content'] ?? null;
        $lesson->course_id = $validated['course_id'];
    
        if ($request->hasFile('video_url')) {
            if ($lesson->video_url && Storage::exists('public/Video/' . $lesson->video_url)) {
                Storage::delete('public/Video/' . $lesson->video_url);
            }
    
            $videoName = time() . '_' . $request->file('video_url')->getClientOriginalName();
            $request->file('video_url')->storeAs('public/Video', $videoName);
            $lesson->video_url = $videoName;
        }
    
        $lesson->save();
    
        return back()->with('success', 'Leçon mise à jour avec succès !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Trouver la leçon
        $lesson = Lesson::findOrFail($id);

        // Supprimer la leçon
        $lesson->delete();

        // Retourner une réponse JSON pour Inertia.js
        return response()->json(['message' => 'Leçon supprimée avec succès !'], 200);
        //return Redirect::back()->with('success', 'Leçon supprimée avec succès !');
    }
    public function manage()
    {
        $lessons = Lesson::with('course:id,title')
            ->select('id', 'title', 'course_id', 'created_at')
            ->paginate(10)
            ->through(fn($lesson) => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'course_title' => optional($lesson->course)->title,
                'created_at' => $lesson->created_at,
            ]);

        return Inertia::render('Admin/LessonManage', ['lessons' => $lessons]);
    }

}
