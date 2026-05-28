<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Attendance;
use App\Mail\AttendanceMail;
use App\Models\ClassAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    public function take($id)
    {
        $user = Auth::user();

        $assignment = ClassAssignment::with([
            'teacher',
            'course',
            'section',
            'subject'
        ])->findOrFail($id);

        if ($user->role === 'teacher') {

            if (!$user->teacher || $assignment->teacher_id !== $user->teacher->id) {
                abort(403);
            }
        }

        $students = Student::where('section_id', $assignment->section_id)
            ->where('course_id', $assignment->course_id)
            ->get();

        // 🔥 GET TODAY'S ATTENDANCE
        $attendance = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', now())
            ->get()
            ->keyBy('student_id');

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
        ]);

        $student = Student::findOrFail($request->student_id);
        $today = now()->toDateString();

        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {

            $minutes = $existing->created_at->diffInMinutes(now());

            // 🔒 REAL LOCK (5 MINUTES)
            if ($minutes >= 5) {
                return back()->with('error', 'Attendance locked after 5 minutes');
            }

            $existing->update([
                'status' => $request->status,
            ]);

        } else {

            Attendance::create([
                'student_id' => $student->id,
                'teacher_id' => auth()->id(),
                'course_id' => $student->course_id,
                'section_id' => $student->section_id,
                'status' => $request->status,
                'date' => now(),
            ]);
        }

        // 📧 EMAIL PARENT
        if ($student->parent_email) {
            Mail::to($student->parent_email)
                ->send(new AttendanceMail($student, $request->status));
        }

        return back();
    }
}