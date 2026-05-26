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

            'student_number' => [
                'required',
                'unique:students,student_number',
            ],

            'fullname' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:students,email',
            ],

            'parent_email' => [
                'required',
                'email',
            ],

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'section_id' => [
                'required',
                'exists:sections,id',
            ],

            'year_level' => [
                'required',
            ],
        ]);

        Student::create($validated);

        return redirect()->back()->with([
            'success' => 'Student added successfully.',
        ]);
    }
    public function update(Request $request, Student $student)
    {
    $validated = $request->validate([
        'student_number' => 'required|unique:students,student_number,' . $student->id,
        'fullname' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email,' . $student->id,
        'parent_email' => 'required|email',
        'course_id' => 'required|exists:courses,id',
        'section_id' => 'required|exists:sections,id',
        'year_level' => 'required',
    ]);

    $student->update($validated);

    return back()->with('success', 'Student updated successfully.');
    }
    public function destroy(Student $student)
    {
    $student->delete();

    return back()->with('success', 'Student deleted successfully.');
    }
}