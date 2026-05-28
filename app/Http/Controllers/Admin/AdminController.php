<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function users()
    {
        return Inertia::render('Admin/Users', [
            'users' => User::latest()->get(),
        ]);
    }

    public function approve(User $user)
    {
        $user->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'User approved successfully.');
    }

    public function reject(User $user)
    {
        $user->delete();

        return back()->with('success', 'User rejected.');
    }


    public function teachers()
{
    return Inertia::render('Admin/Teachers', [
        'teachers' => \App\Models\Teacher::all(),
    ]);
}

public function teacherClasses($id)
{
    $teacher = \App\Models\Teacher::findOrFail($id);

    $assignments = \App\Models\ClassAssignment::with([
        'course',
        'section',
        'subject'
    ])
    ->where('teacher_id', $id)
    ->get();

    return Inertia::render('Admin/TeacherClasses', [
        'teacher' => $teacher,
        'assignments' => $assignments,
    ]);
}public function studentSubjects($id)
{
    $student = Student::with('subjects')->findOrFail($id);

    return Inertia::render('Admin/StudentSubjects', [
        'student' => $student,
        'subjects' => $student->subjects,
    ]);
}
public function subjectClasses($id)
{
    $subject = Subject::findOrFail($id);

    $classes = ClassAssignment::with(['teacher','course','section'])
        ->where('subject_id', $id)
        ->get();

    return Inertia::render('Admin/SubjectClasses', [
        'subject' => $subject,
        'classes' => $classes,
    ]);
}
}