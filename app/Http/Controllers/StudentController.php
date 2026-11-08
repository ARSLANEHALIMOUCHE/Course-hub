<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::where('status', 'published')
            ->with(['subject', 'teacher'])
            ->when($request->filled('subject'), fn ($query) => $query->where('subject_id', $request->integer('subject')))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($subQuery) use ($request): void {
                $term = '%'.$request->string('search').'%';
                $subQuery->where('title', 'like', $term)->orWhere('description', 'like', $term);
            }))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('student.courses.index', [
            'courses' => $courses,
            'subjects' => Subject::orderBy('name')->get(),
            'selectedSubject' => $request->input('subject', ''),
        ]);
    }

    public function show(Course $course): View
    {
        abort_unless($course->status === 'published', 404);

        return view('student.courses.show', ['course' => $course->load(['subject', 'teacher', 'files'])]);
    }

    public function download(Request $request, Course $course, CourseFile $file): StreamedResponse
    {
        abort_unless($file->course_id === $course->id, 404);
        abort_unless(
            ($request->user()->role === 'student' && $course->status === 'published')
                || ($request->user()->role === 'teacher' && $course->teacher_id === $request->user()->id)
                || $request->user()->role === 'admin',
            403
        );
        abort_unless(Storage::disk('local')->exists($file->path), 404, 'Le fichier demandé est introuvable.');

        return Storage::disk('local')->download($file->path, $file->original_name);
    }
}
