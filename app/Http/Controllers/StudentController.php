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
}
public function update(Request $request, Student $student)
{
    $request->validate([
        'student_number' => 'required|string|max:255',
        'fullname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'parent_email' => 'nullable|email|max:255',
        'course_id' => 'nullable|exists:courses,id',
        'section_id' => 'nullable|exists:sections,id',
        'year_level' => 'nullable|string|max:50',
    ]);

    $student->update([
        'student_number' => $request->student_number,
        'fullname' => $request->fullname,
        'email' => $request->email,
        'parent_email' => $request->parent_email,
        'course_id' => $request->course_id,
        'section_id' => $request->section_id,
        'year_level' => $request->year_level,
    ]);}

}