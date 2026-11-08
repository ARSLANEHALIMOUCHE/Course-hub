<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseFile;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()
            ->with('subject')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('teacher.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('teacher.courses.form', ['course' => new Course(), 'subjects' => Subject::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->courseData($request);
        $files = $data['files'] ?? [];
        unset($data['files']);
        $course = $request->user()->courses()->create($data);
        $this->storeFiles($course, $files);

        return redirect()->route('teacher.courses.index')->with('success', 'Le cours a été créé.');
    }

    public function edit(Request $request, Course $course): View
    {
        $this->authorizeOwner($request, $course);

        return view('teacher.courses.form', ['course' => $course->load('files'), 'subjects' => Subject::orderBy('name')->get()]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        $data = $this->courseData($request);
        $files = $data['files'] ?? [];
        unset($data['files']);
        $course->update($data);
        $this->storeFiles($course, $files);

        return redirect()->route('teacher.courses.edit', $course)->with('success', 'Le cours a été mis à jour.');
    }

    public function toggleStatus(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        $status = $course->status === 'published' ? 'draft' : 'published';
        $course->update(['status' => $status, 'published_at' => $status === 'published' ? now() : null]);

        return back()->with('success', $status === 'published' ? 'Le cours est publié.' : 'Le cours est maintenant en brouillon.');
    }

    public function deleteFile(Request $request, Course $course, CourseFile $file): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        abort_unless($file->course_id === $course->id, 404);
        Storage::disk('local')->delete($file->path);
        $file->delete();

        return back()->with('success', 'La pièce jointe a été supprimée.');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwner($request, $course);
        DB::transaction(function () use ($course): void {
            $paths = $course->files()->pluck('path');
            $course->delete();
            Storage::disk('local')->delete($paths->all());
        });

        return redirect()->route('teacher.courses.index')->with('success', 'Le cours et ses fichiers ont été supprimés.');
    }

    private function courseData(Request $request): array
    {
        return $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:50000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:pdf', 'max:10240'],
        ]);
    }

    private function storeFiles(Course $course, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store('course-files/'.$course->id, 'local');
            $course->files()->create(['path' => $path, 'original_name' => $file->getClientOriginalName()]);
        }
    }

    private function authorizeOwner(Request $request, Course $course): void
    {
        abort_unless($course->teacher_id === $request->user()->id, 403);
    }
}
