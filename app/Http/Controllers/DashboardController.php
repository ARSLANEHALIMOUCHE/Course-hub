<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('dashboard.admin', [
                'usersCount' => User::count(),
                'teacherCount' => User::where('role', 'teacher')->count(),
                'studentCount' => User::where('role', 'student')->count(),
                'publishedCount' => Course::where('status', 'published')->count(),
                'draftCount' => Course::where('status', 'draft')->count(),
                'latestUsers' => User::latest()->limit(5)->get(),
            ]);
        }

        if ($user->role === 'teacher') {
            $courses = $user->courses()->with('subject')->latest()->limit(5)->get();

            return view('dashboard.teacher', [
                'courses' => $courses,
                'publishedCount' => $user->courses()->where('status', 'published')->count(),
                'draftCount' => $user->courses()->where('status', 'draft')->count(),
                'subjectsCount' => Subject::count(),
            ]);
        }

        if ($user->role === 'student') {
            return view('dashboard.student', [
                'courses' => Course::where('status', 'published')
                    ->with(['subject', 'teacher'])
                    ->latest('published_at')
                    ->limit(6)
                    ->get(),
                'subjectsCount' => Subject::count(),
                'publishedCount' => Course::where('status', 'published')->count(),
            ]);
        }

        Auth::logout();

        return redirect()->route('login');
    }
}
