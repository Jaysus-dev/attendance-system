<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Section;
use App\Models\Student;

use Illuminate\Http\Request;

use Inertia\Inertia;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Students
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return Inertia::render('Students', [

            'students' => Student::with([
                'course',
                'section',
            ])->latest()->get(),
            'courses' => Course::all(),
            'sections' => Section::all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Student
    |--------------------------------------------------------------------------
    */

public function store(Request $request)
{
    $validated = $request->validate([
        'student_number' => 'required|unique:students',
        'fullname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'parent_email' => 'nullable|email',
        'course_id' => 'required|exists:courses,id',
        'section_id' => 'required|exists:sections,id',
        'year_level' => 'required',
    ]);

     DB::transaction(function () use ($validated) {

        // 1. CREATE USER FIRST
        $user = User::create([
            'name' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'approved',
        ]);

        // 2. Create student profile
         Student::create([
            'user_id' => $user->id,   // 🔥 THIS FIXES YOUR ERROR
            'student_number' => $validated['student_number'],
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'parent_email' => $validated['parent_email'] ?? null,
            'course_id' => $validated['course_id'],
            'section_id' => $validated['section_id'],
            'year_level' => $validated['year_level'],
        ]);
    });

    return back()->with('success', 'Student created successfully.');
}}