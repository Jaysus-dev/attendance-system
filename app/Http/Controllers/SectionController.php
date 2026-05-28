<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;


class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Sections', [
            'sections' => Section::with('course')->latest()->get(),
            'courses' => Course::all(),
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
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'section_name' => 'required',
        'year_level' => 'required',
    ]);

    Section::create([
        'course_id' => $request->course_id,
        'section_name' => $request->section_name,
        'year_level' => $request->year_level,
    ]);

    return back()->with('success', 'Section created');
}
    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
        'section_name' => 'required',
        'year_level' => 'required',
    ]);

    $section = Section::findOrFail($id);

    $section->update([
        'course_id' => $request->course_id,
        'section_name' => $request->section_name,
        'year_level' => $request->year_level,
    ]);

    return back()->with('success', 'Section updated');
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Section $section)
    {
    $section->delete();
    }
}
