<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ClassAssignmentController;
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

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', function () { return Inertia::render('Dashboard'); })->name('dashboard');

    // STUDENTS
    Route::get('/students', [StudentController::class,'index',])->name('students'); 
    Route::post('/students', [StudentController::class,'store',])->name('students.store'); 
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update'); 
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // TEACHERS
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers'); 
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store'); 
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update'); 
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // COURSES
    Route::get('/courses', [CourseController::class, 'index']) ->name('courses'); 
    Route::post('/courses', [CourseController::class, 'store']) ->name('courses.store'); 
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update'); 
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // SUBJECTS
    Route::get('/subjects', [SubjectController::class, 'index']) ->name('subjects'); 
    Route::post('/subjects', [SubjectController::class, 'store']) ->name('subjects.store'); 
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update'); 
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // SECTIONS
    Route::get('/sections', [SectionController::class, 'index']) ->name('sections'); 
    Route::post('/sections', [SectionController::class, 'store']) ->name('sections.store'); 
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update'); 
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

    // ASSIGNMENTS
    Route::get('/assignments', [ClassAssignmentController::class, 'index'])->name('assignments'); 
    Route::post('/assignments', [ClassAssignmentController::class, 'store'])->name('assignments.store');
    Route::delete('/assignments/{id}', [ClassAssignmentController::class, 'destroy']);

    // REPORTS
    Route::get('/reports', function () { return Inertia::render('Reports'); })->name('reports');
  
    // SETTINGS
    Route::get('/settings', function () { return Inertia::render('Settings'); })->name('settings');

    // USERS (ADMIN ONLY)
    Route::get('/users', [AdminController::class, 'users'])
    ->name('admin.users');

    Route::put('/users/{user}/approve', [AdminController::class, 'approve'])
    ->name('admin.users.approve');

    Route::delete('/users/{user}/reject', [AdminController::class, 'reject'])
    ->name('admin.users.reject');

});

    Route::middleware(['auth', 'role:teacher,admin'])->group(function () {

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance');

    // FUTURE:
    // Route::get('/my-classes')
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/pending-approval', function () {
    return Inertia::render('Auth/PendingApproval');
})->name('pending.approval');

require __DIR__.'/auth.php';
