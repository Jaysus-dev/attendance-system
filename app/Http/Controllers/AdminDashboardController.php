<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------
        | BASIC COUNTS
        |--------------------------------------
        */
        $totalStudents = Student::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses  = Course::count();
        $totalSubjects = Subject::count();
        $totalSections = Section::count();

        /*
        |--------------------------------------
        | ACCOUNT STATUS
        |--------------------------------------
        */
        $pendingUsers = User::where('status', 'pending')->count();
        $approvedUsers = User::where('status', 'approved')->count();

        /*
        |--------------------------------------
        | ATTENDANCE SUMMARY
        |--------------------------------------
        */
        $present = Attendance::whereRaw('LOWER(status) = ?', ['present'])->count();
        $absent  = Attendance::whereRaw('LOWER(status) = ?', ['absent'])->count();
        $late    = Attendance::whereRaw('LOWER(status) = ?', ['late'])->count();

        // 🔥 TOTAL ATTENDANCE (NEW)
        $totalAttendance = $present + $absent + $late;

        /*
        |--------------------------------------
        | WEEKLY ATTENDANCE (LAST 7 DAYS)
        |--------------------------------------
        */
        $weeklyRaw = Attendance::select(
                DB::raw('DATE(date) as date'),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'present' THEN 1 ELSE 0 END) as present"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'absent' THEN 1 ELSE 0 END) as absent"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'late' THEN 1 ELSE 0 END) as late")
            )
            ->whereDate('date', '>=', now()->subDays(6))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // ensure 7 days always exist
        $weekly = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $weekly->push([
                'date' => $date,
                'present' => (int) ($weeklyRaw[$date]->present ?? 0),
                'absent'  => (int) ($weeklyRaw[$date]->absent ?? 0),
                'late'    => (int) ($weeklyRaw[$date]->late ?? 0),
            ]);
        }

        /*
        |--------------------------------------
        | RECENT ATTENDANCE
        |--------------------------------------
        */
        $recent = Attendance::with([
                'student',
                'classAssignment.course',
                'classAssignment.subject'
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------
        | RETURN DATA
        |--------------------------------------
        */
        return Inertia::render('Dashboard', [
            'stats' => [
                'students' => $totalStudents,
                'teachers' => $totalTeachers,
                'courses'  => $totalCourses,
                'subjects' => $totalSubjects,
                'sections' => $totalSections,

                'present' => $present,
                'absent'  => $absent,
                'late'    => $late,

                // 🔥 NEW TOTAL
                'total_attendance' => $totalAttendance,

                'pending'  => $pendingUsers,
                'approved' => $approvedUsers,
            ],

            'weeklyAttendance' => $weekly,
            'recent' => $recent,
        ]);
    }
}