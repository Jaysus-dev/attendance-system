<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassAssignmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Assignments', [
            'teachers' => Teacher::all(),
            'courses' => Course::all(),
            'sections' => Section::with('course')->get(),
            'subjects' => Subject::all(),

            'assignments' => ClassAssignment::with([
                'teacher',
                'course',
                'section',
                'subject'
            ])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',

            // IMPORTANT FIX
            'year_level' => 'nullable|string',
        ]);

        ClassAssignment::create([
            'teacher_id' => $request->teacher_id,
            'course_id' => $request->course_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,

            // SAFE HANDLING
            'year_level' => $request->year_level ?? null,
        ]);

        return back()->with('success', 'Assigned successfully');
    }

    public function destroy($id)
    {
        ClassAssignment::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }
}