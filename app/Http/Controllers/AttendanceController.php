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

        if (!$user) {
            abort(403);
        }

        // =========================
        // ADMIN: ALL ASSIGNMENTS
        // =========================
        if ($user->role === 'admin') {

            $assignments = ClassAssignment::with([
                'course',
                'section',
                'subject'
            ])->get();
        }

        // =========================
        // TEACHER: OWN ONLY
        // =========================
        elseif ($user->role === 'teacher') {

            if ($user->status !== 'approved') {
                abort(403);
            }

            if (!$user->teacher) {
                abort(403, 'Teacher profile not found');
            }

            $assignments = ClassAssignment::with([
                'course',
                'section',
                'subject'
            ])
            ->where('teacher_id', $user->teacher->id)
            ->get();
        }

        else {
            abort(403);
        }

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
    $assignment = ClassAssignment::with([
        'course',
        'section',
        'subject'
    ])->findOrFail($id);

    $students = \App\Models\Student::where('section_id', $assignment->section_id)
        ->where('course_id', $assignment->course_id)
        ->get();

    return Inertia::render('Attendance/Take', [
        'assignment' => $assignment,
        'students' => $students,
    ]);
}
}