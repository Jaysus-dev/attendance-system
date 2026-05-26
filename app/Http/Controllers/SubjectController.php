<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Subjects', [
            'subjects' =>  Subject::with('teacher')->get(),
            'teachers' => Teacher::select('id', 'fullname')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|max:20|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        
    
        Subject::create($validated);

        return redirect()->back()->with('success', 'Subject created successfully.');
    }
}
