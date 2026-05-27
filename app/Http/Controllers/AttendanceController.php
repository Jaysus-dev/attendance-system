<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $user = Auth::user();

    if (!$user) {
        abort(403);
    }

    // ADMIN: see everything
    if ($user->role === 'admin') {
        $assignments = ClassAssignment::with(['course','section','subject'])->get();
    }

    // TEACHER: only own
    elseif ($user->role === 'teacher') {

        if ($user->status !== 'approved') {
            abort(403);
        }

        if (!$user->teacher) {
            abort(403, 'Teacher profile not found');
        }

        $assignments = ClassAssignment::with(['course','section','subject'])
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
