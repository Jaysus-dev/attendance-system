<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if (!$user->teacher) {
        abort(403);
    }

    $assignments = ClassAssignment::with([
        'course',
        'section',
        'subject'
    ])
    ->where('teacher_id', $user->teacher->id)
    ->get();

    return Inertia::render('Attendance', [
        'assignments' => $assignments,
    ]);
}

    // =====================================
    // STUDENT VIEW (SAFE VERSION)
    // =====================================
    public function studentView()
    {
        $user = Auth::user();

        if (!$user || !$user->student) {
            abort(403, 'Student profile not found');
        }

        $student = $user->student;

        $attendance = Attendance::where('section_id', $student->section_id)
            ->where('course_id', $student->course_id)
            ->latest()
            ->get();

        return Inertia::render('Student/MyAttendance', [
            'attendance' => $attendance,
        ]);
    }
public function take($id)
{
    $user = Auth::user();

    $assignment = ClassAssignment::with([
        'teacher',
        'course',
        'section',
        'subject'
    ])->findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | ADMIN CAN ACCESS ALL
    |--------------------------------------------------------------------------
    */
    if ($user->role === 'admin') {

        $students = \App\Models\Student::where(
            'section_id',
            $assignment->section_id
        )
        ->where(
            'course_id',
            $assignment->course_id
        )
        ->get();

        return Inertia::render('Attendance/Take', [
            'assignment' => $assignment,
            'students' => $students,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER CAN ACCESS ONLY OWN CLASS
    |--------------------------------------------------------------------------
    */
    if ($user->role === 'teacher') {

        if (!$user->teacher) {
            abort(403);
        }

        // SECURITY CHECK
        if ($assignment->teacher_id !== $user->teacher->id) {
            abort(403, 'Unauthorized');
        }

        $students = \App\Models\Student::where(
            'section_id',
            $assignment->section_id
        )
        ->where(
            'course_id',
            $assignment->course_id
        )
        ->get();

        return Inertia::render('Attendance/Take', [
            'assignment' => $assignment,
            'students' => $students,
        ]);
    }

    abort(403);
}
}