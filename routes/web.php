<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/status', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
        Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [AdminController::class, 'deleteSubject'])->name('subjects.destroy');
    });

    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function (): void {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::patch('/courses/{course}/status', [CourseController::class, 'toggleStatus'])->name('courses.status');
        Route::delete('/courses/{course}/files/{file}', [CourseController::class, 'deleteFile'])->name('courses.files.destroy');
    });

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function (): void {
        Route::get('/courses', [StudentController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [StudentController::class, 'show'])->name('courses.show');
    });

    Route::get('/courses/{course}/files/{file}', [StudentController::class, 'download'])
        ->name('courses.files.download');
});
