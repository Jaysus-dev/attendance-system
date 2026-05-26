<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Course;

use Illuminate\Http\Request;

use Inertia\Inertia;

class TeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display Teachers
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return Inertia::render('Teachers', [

            'teachers' => Teacher::with('course')
                ->latest()
                ->get(),

            'courses' => Course::all(),
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

            'department' => [
                'required',
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
}