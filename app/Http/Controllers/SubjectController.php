<?php
namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Subjects', [
            'subjects' => Subject::with('teacher', 'course')->get(),
            'teachers' => Teacher::select('id', 'fullname')->get(),
            'courses' => Course::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        Subject::create($validated);

        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    // ✅ ADD THIS: UPDATE METHOD
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $subject->update($validated);

        return redirect()->back()->with('success', 'Subject updated successfully.');
    }

    // ✅ ADD THIS: DELETE METHOD
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
}