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
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'section_name' => ['required', 'string', 'max:255'],
        ]);

        Section::create($validated);

        return redirect()->back()->with('success', 'Section created successfully.');
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
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        //
    }
}
