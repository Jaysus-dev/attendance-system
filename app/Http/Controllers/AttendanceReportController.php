<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $courseId = $request->course_id;
        $subjectId = $request->subject_id;

        $query = Attendance::with([
            'student',
            'classAssignment.course',
            'classAssignment.subject',
        ])
        ->whereBetween('date', [$startDate, $endDate]);

        // teacher restriction
        if ($user->role === 'teacher') {
            $query->where('teacher_id', $user->teacher->id);
        }

        // course filter
        if (!empty($courseId) && $courseId !== 'all') {
            $query->whereHas('classAssignment', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        // subject filter
        if (!empty($subjectId) && $subjectId !== 'all') {
            $query->whereHas('classAssignment', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return Inertia::render('Reports', [
            'attendances' => $attendances,
            'courses' => Course::all(),
            'subjects' => Subject::all(),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'course_id' => $courseId,
                'subject_id' => $subjectId,
            ],
        ]);
    }
}