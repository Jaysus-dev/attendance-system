<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\AttendanceMail;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->teacher) {
            abort(403);
        }

        $assignments = ClassAssignment::with(['course', 'section', 'subject'])
            ->where('teacher_id', $user->teacher->id)
            ->get();

        return Inertia::render('Attendance', [
            'assignments' => $assignments,
        ]);
    }

    public function take($id)
    {
        $user = Auth::user();

        $assignment = ClassAssignment::with(['teacher', 'course', 'section', 'subject'])
            ->findOrFail($id);

        if ($user->role === 'teacher') {
            if (!$user->teacher || $assignment->teacher_id !== $user->teacher->id) {
                abort(403);
            }
        }

        $students = Student::where('section_id', $assignment->section_id)
            ->where('course_id', $assignment->course_id)
            ->get();

        // TODAY ATTENDANCE MAP
        $attendance = Attendance::where('class_assignment_id', $assignment->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', now())
            ->get()
            ->keyBy(fn ($item) =>
                $item->student_id . '-' . $item->class_assignment_id
            );

        return Inertia::render('Attendance/Take', [
            'assignment' => $assignment,
            'students' => $students,
            'attendance' => $attendance,
        ]);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|string',
            'class_assignment_id' => 'required|exists:class_assignments,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $today = now()->toDateString();

        $existing = Attendance::where('student_id', $student->id)
            ->where('class_assignment_id', $request->class_assignment_id)
            ->whereDate('date', $today)
            ->first();

        // 🔒 IMMEDIATE LOCK (NO EDIT ALLOWED)
        if ($existing) {
            return back()->with('error', 'Attendance already marked and locked.');
        }

        Attendance::create([
            'student_id' => $student->id,
            'teacher_id' => auth()->id(),
            'class_assignment_id' => $request->class_assignment_id,
            'course_id' => $student->course_id,
            'section_id' => $student->section_id,
            'status' => $request->status,
            'date' => now(),
            'marked_at' => now(),
        ]);

        // EMAIL
        try {
            if ($student->parent_email) {
                Mail::to($student->parent_email)
                    ->send(new AttendanceMail($student, $request->status));
            }
        } catch (\Exception $e) {
            \Log::error('Attendance email failed: ' . $e->getMessage());
        }

        return back();
    }
}