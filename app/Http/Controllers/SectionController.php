<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Section::with(['grade', 'school'])->latest();
            if (!auth()->user()->isMasterAdmin()) {
                $query->where('school_id', auth()->user()->school_id);
            } elseif ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }
            if ($request->has('branch_id') && !empty($request->branch_id)) {
                $query->where('branch_id', $request->branch_id);
            }
            $sections = $query->get();
            return response()->json($sections);
        }

        $grades = \App\Models\Grade::orderBy('name', 'asc');
        $admins = collect();

        if (auth()->user()->isMasterAdmin()) {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->with('school')->get();
        } else {
            $grades->where('school_id', auth()->user()->school_id);
        }

        $grades = $grades->get();
        return view('sections.index', compact('grades', 'admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($this->hasDuplicate($request)) {
             return response()->json(['errors' => ['duplicate' => ['Section with this Name already exists in this Class.']]], 422);
        }

        $section = \App\Models\Section::create($validated);
        return response()->json(['success' => 'Section created successfully.', 'section' => $section]);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\Section::with('grade')->findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $section = \App\Models\Section::findOrFail($id);
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($this->hasDuplicate($request, $id)) {
             return response()->json(['errors' => ['duplicate' => ['Section with this Name already exists in this Class.']]], 422);
        }

        $section->update($validated);
        return response()->json(['success' => 'Section updated successfully.']);
    }

    private function hasDuplicate(Request $request, $ignoreId = null)
    {
        $query = \App\Models\Section::where('school_id', $request->school_id)
            ->where('grade_id', $request->grade_id)
            ->where('name', $request->name);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    public function destroy(string $id)
    {
        $section = \App\Models\Section::findOrFail($id);
        $section->delete();
        return response()->json(['success' => 'Section deleted successfully.']);
    }
}
