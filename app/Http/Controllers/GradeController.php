<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Grade::with(['school', 'sections', 'teacher'])->orderBy('name', 'asc');
            if (!auth()->user()->isMasterAdmin()) {
                $query->where('school_id', auth()->user()->school_id);
            } elseif ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            if ($request->has('teacher_id') && !empty($request->teacher_id)) {
                $query->where('teacher_id', $request->teacher_id);
            }
            $grades = $query->get();
            return response()->json($grades);
        }
        
        $admins = collect();
        $teachers = \App\Models\Teacher::orderBy('name', 'asc');
        
        if (auth()->user()->isMasterAdmin()) {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->with('school')->get();
        } else {
            $teachers->where('school_id', auth()->user()->school_id);
        }
        
        $teachers = $teachers->get();
        return view('grades.index', compact('admins', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($this->hasDuplicate($request)) {
             return response()->json(['errors' => ['duplicate' => ['Class with this Name already exists in this School.']]], 422);
        }

        $grade = Grade::create($validated);

        return response()->json(['success' => 'Class created successfully.', 'grade' => $grade]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grade = Grade::findOrFail($id);
        return response()->json($grade);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $grade = Grade::findOrFail($id);
 
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($this->hasDuplicate($request, $id)) {
             return response()->json(['errors' => ['duplicate' => ['Class with this Name already exists in this School.']]], 422);
        }

        $grade->update($validated);

        return response()->json(['success' => 'Class updated successfully.', 'grade' => $grade]);
    }

    private function hasDuplicate(Request $request, $ignoreId = null)
    {
        $query = Grade::where('school_id', $request->school_id)
            ->where('name', $request->name);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return response()->json(['success' => 'Class deleted successfully.']);
    }
}
