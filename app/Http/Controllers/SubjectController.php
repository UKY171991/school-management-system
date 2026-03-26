<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Subject::with(['school', 'grade'])->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
             if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }
            $subjects = $query->get();
            return response()->json($subjects);
        }
        $schools = \App\Models\School::all();
        $grades = \App\Models\Grade::all();
        return view('subjects.index', compact('schools', 'grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:subjects,code',
        ]);

        if (empty($validated['code'])) {
            // Auto-generate code: SUB-RANDOM
            $validated['code'] = strtoupper(substr($validated['name'], 0, 3)) . rand(100, 999);
            // Ensure uniqueness (simple retry)
            while(\App\Models\Subject::where('code', $validated['code'])->exists()) {
                $validated['code'] = strtoupper(substr($validated['name'], 0, 3)) . rand(100, 999);
            }
        }

        $subject = \App\Models\Subject::create($validated);
        return response()->json(['success' => 'Subject created successfully.', 'subject' => $subject]);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\Subject::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:subjects,code,'.$subject->id,
        ]);

        if (empty($validated['code']) && empty($subject->code)) {
             $validated['code'] = strtoupper(substr($validated['name'], 0, 3)) . rand(100, 999);
        }

        $subject->update($validated);
        return response()->json(['success' => 'Subject updated successfully.']);
    }

    public function destroy(string $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $subject->delete();
        return response()->json(['success' => 'Subject deleted successfully.']);
    }
}
