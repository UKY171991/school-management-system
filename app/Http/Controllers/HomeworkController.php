<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Homework::with(['subject', 'section.grade', 'school'])->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $homework = $query->get();
            return response()->json($homework);
        }
        $schools = \App\Models\School::all();
        return view('homework.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        $homework = \App\Models\Homework::create($validated);
        return response()->json(['success' => 'Homework assigned successfully.', 'homework' => $homework]);
    }

    public function show($id)
    {
        return response()->json(\App\Models\Homework::with('subject', 'section.grade')->findOrFail($id));
    }

    public function destroy($id)
    {
        $homework = \App\Models\Homework::findOrFail($id);
        $homework->delete();
        return response()->json(['success' => 'Homework removed.']);
    }
}
