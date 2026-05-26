<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canResetPassword' => Route::has('password.request'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    //Student Page
    Route::get('/students', [StudentController::class,'index',])->name('students');
    Route::post('/students', [StudentController::class,'store',])->name('students.store');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');


    Route::get('/courses', [CourseController::class, 'index'])
    ->name('courses');
    Route::post('/courses', [CourseController::class, 'store'])
    ->name('courses.store');


    Route::get('/subjects', [SubjectController::class, 'index'])
    ->name('subjects');
    Route::post('/subjects', [SubjectController::class, 'store'])
    ->name('subjects.store');

    Route::get('/sections', [SectionController::class, 'index'])
    ->name('sections');
    Route::post('/sections', [SectionController::class, 'store'])
    ->name('sections.store');

    Route::get('/attendance', function () {
        return Inertia::render('Attendance');
    })->name('attendance');
    Route::get('/users', function () {
        return Inertia::render('Users');
    })->name('users');
    Route::get('/reports', function () {
        return Inertia::render('Reports');
    })->name('reports');
    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->name('settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
