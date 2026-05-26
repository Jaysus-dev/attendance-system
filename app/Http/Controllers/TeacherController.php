<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Course;

use Illuminate\Http\Request;

use Inertia\Inertia;

class TeacherController extends Controller
{
    private array $positions = [
        'Instructor',
        'Assistant Instructor',
        'Lecturer',
        'Assistant Professor',
        'Associate Professor',
        'Professor',
        'Department Chair',
        'Program Head',
        'Dean',
        'Part-time Instructor',
        'Visiting Lecturer',
        'Lab Instructor',
        'Research Assistant',
    ];

    public function index()
    {
        return Inertia::render('Teachers', [

            'teachers' => Teacher::with('course')
                ->latest()
                ->get(),

            'courses' => Course::all(),
            'positions' => $this->positions,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Teacher
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'employee_number' => [
                'required',
                'unique:teachers,employee_number',
            ],

            'fullname' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
                'unique:teachers,email',
            ],

            'course_id' => [
                'required',
                'exists:courses,id',
            ],


            'position' => [
                'required',
            ],
        ]);

        Teacher::create($validated);

        return redirect()->back()->with([
            'success' => 'Teacher added successfully.',
        ]);
    }
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'employee_number' => [
                'required',
                'unique:teachers,employee_number,' . $teacher->id,
            ],

            'fullname' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:teachers,email,' . $teacher->id,
            ],

            'course_id' => [
                'required',
                'exists:courses,id',
            ],

            'position' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $teacher->update($validated);

        return back()->with('success', 'Teacher updated successfully.');
    }
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return back()->with('success', 'Teacher deleted successfully.');
    }
}