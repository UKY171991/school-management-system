<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamType;
use App\Models\School;

class ExamTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ExamType::with('school')->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
                return response()->json($query->get());
            }
            return response()->json([]);
        }
        $schools = School::all();
        return view('exam-types.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($this->hasDuplicate($request)) {
             return response()->json(['errors' => ['duplicate' => ['Exam Type with this Name already exists in this School.']]], 422);
        }

        $examType = ExamType::create($validated);
        return response()->json(['success' => 'Exam Type created successfully.', 'data' => $examType]);
    }

    public function show($id)
    {
        return response()->json(ExamType::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $examType = ExamType::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($this->hasDuplicate($request, $id)) {
            return response()->json(['errors' => ['duplicate' => ['Exam Type with this Name already exists in this School.']]], 422);
        }

        $examType->update($validated);
        return response()->json(['success' => 'Exam Type updated successfully.']);
    }

    public function destroy($id)
    {
        ExamType::findOrFail($id)->delete();
        return response()->json(['success' => 'Exam Type deleted successfully.']);
    }

    private function hasDuplicate(Request $request, $ignoreId = null)
    {
        $query = ExamType::where('school_id', $request->school_id)
            ->where('name', $request->name);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
