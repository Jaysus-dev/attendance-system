<?php

use App\Http\Controllers\ProfileController;
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
    Route::get('/students', function () {
        return Inertia::render('Students');
    })->name('students');
    Route::get('/teachers', function () {
        return Inertia::render('Teachers');
    })->name('teachers');
    Route::get('/classes', function () {
        return Inertia::render('Classes');
    })->name('classes');
    Route::get('/subjects', function () {
        return Inertia::render('Subjects');
    })->name('subjects');
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
