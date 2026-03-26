<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = \App\Models\Student::with('grade')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $grades = \App\Models\Grade::all();
        return view('students.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'roll_number' => 'required|string|unique:students,roll_number',
            'grade_id' => 'required|exists:grades,id',
            'dob' => 'nullable|date',
        ]);

        \App\Models\Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(string $id)
    {
        $student = \App\Models\Student::with('grade')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $grades = \App\Models\Grade::all();
        return view('students.edit', compact('student', 'grades'));
    }

    public function update(Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'roll_number' => 'required|string|unique:students,roll_number,' . $student->id,
            'grade_id' => 'required|exists:grades,id',
            'dob' => 'nullable|date',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
