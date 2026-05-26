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
            'teacher_id' => 'required',
            'course_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
        ]);

        ClassAssignment::create($request->all());

        return back()->with('success', 'Assigned successfully');
    }

    public function destroy($id)
    {
        ClassAssignment::findOrFail($id)->delete();

        return back();
    }
}
