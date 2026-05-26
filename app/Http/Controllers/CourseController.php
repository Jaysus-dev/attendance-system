<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Courses', [
            'courses' => Course::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_code' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:20|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
        ]);

        Course::create($validated);

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
{
    $validated = $request->validate([
        'department_code' => 'required',
        'department_name' => 'required',
        'course_code' => 'required',
        'course_name' => 'required',
    ]);

    $course->update($validated);

    return back()->with('success', 'Course updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
{
    $course->delete();

    return back()->with('success', 'Course deleted successfully.');
}
}
