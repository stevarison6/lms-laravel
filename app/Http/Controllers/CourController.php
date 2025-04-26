<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Quiz;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Recherche par titre de cours
        $search = $request->query('search');

        $query = Course::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $courses = $query->paginate(6)->through(function ($course) use ($user) {
            $enrollment = Enrollment::where('user_id', $user->id)
                                    ->where('course_id', $course->id)
                                    ->first();
       $firstLesson = Lesson::where('course_id',
        $course->id)->orderBy('id')->first();

            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'photo' => asset('storage/Photo/' . $course->photo_path_course),
                'enrolled' => $enrollment !== null,
                'last_lesson_id' => $enrollment && $enrollment->last_lesson_id ? $enrollment->last_lesson_id
                                            :
                    ($firstLesson ? $firstLesson->id : null),
            ];
        });

        return Inertia::render('CoursList', [
            'courses' => $courses,
            'filters' => [
                'search' => $search
            ],
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('create-resource')) {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer cette ressource.');
        }
        $instructors = User::where('role', 'instructor')->select('id', 'name')->get();
        return inertia('Admin/CreateCourse', ['instructors' => $instructors]);
    }
    public function manage()
    {
        $courses = Course::with('instructor')->orderBy('created_at', 'desc')->get();

    return inertia('Admin/CourseManage', [
        'courses' => $courses
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo_path_course' => 'nullable|image|max:2048',
            'instructor_id' => 'required|exists:users,id',
        ]);

        $photoName = null;

        if ($request->hasFile('photo_path_course')) {
            $photoName = $request->file('photo_path_course')->store('Photo', 'public');
            $photoName = basename($photoName);
        }

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'photo_path_course' => $photoName,
            'instructor_id' => $request->instructor_id,
        ]);

        return back()->with('success', 'Cours créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        // Chercher le cours par ID
        $course = Course::findOrFail($id);

        $idquiz=Quiz::select('id')->where('course_id', $id)->orderBy('id')->first();
        //dd($idquiz->id);


        // Récupérer les leçons et vérifier si elles sont terminées
        $lessons = $course->lessons->map(function ($lesson) use ($user) {
            $progress = Progress::where('user_id', $user->id)
                                ->where('lesson_id', $lesson->id)
                                ->first();

            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'completed' => $progress ? $progress->completed : false,
            ];
        });

        return Inertia::render('CoursContent', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'photo' => asset('storage/Photo/' . $course->photo_path_course),
                'lessons' => $lessons,
                'firstquiz'=>$idquiz->id,
                'instructor' => $user->id == $course->instructor_id,
            ]
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $instructors = User::where('role', 'instructor')->get(['id', 'name']);
        return Inertia::render('Admin/CourseEdit', [
            'courseData' => $course,
            'instructors' => $instructors
        ]);
    }

    //Inscription
    public function inscrir($courseid){
        //dd(request()->url());
        $user=User::find(auth()->id());
        $user->enrolledCourses()->attach($courseid);
        
        return redirect()->route('cours.show',['id'=>$courseid]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'instructor_id' => 'required|exists:users,id',
        'photo_path_course' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('photo_path_course')) {
        $photo = $request->file('photo_path_course');
        $filename = time().'_'.$photo->getClientOriginalName();
        $photo->storeAs('public/Photo', $filename);
        $validated['photo_path_course'] = $filename;
    } else {
        unset($validated['photo_path_course']); // Ne pas toucher si aucune nouvelle photo.
    }

    $course->update($validated);

    return redirect()->back()->with('success', 'Cours modifié avec succès !');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course.manage')->with('success', 'Cours supprimé avec succès.');
    }
}
