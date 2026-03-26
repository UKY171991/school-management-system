<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Salary::with('teacher', 'school')->latest();

            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }

            if ($request->has('teacher_id') && $request->teacher_id) {
                $query->where('teacher_id', $request->teacher_id);
            }

            return response()->json($query->get());
        }
        $schools = \App\Models\School::all();
        $teachers = \App\Models\Teacher::all();
        return view('payroll.index', compact('teachers', 'schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:paid,unpaid',
        ]);

        if ($this->hasDuplicate($request)) {
             return response()->json(['errors' => ['duplicate' => ['Salary for this Teacher, Month and Year already exists.']]], 422);
        }

        $salary = \App\Models\Salary::create($validated);
        return response()->json(['success' => 'Payroll record created.', 'salary' => $salary]);
    }

    public function show($id)
    {
        return response()->json(\App\Models\Salary::with('teacher')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $salary = \App\Models\Salary::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|exists:teachers,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:paid,unpaid',
        ]);

        if ($this->hasDuplicate($request, $id)) {
             return response()->json(['errors' => ['duplicate' => ['Salary for this Teacher, Month and Year already exists.']]], 422);
        }

        $salary->update($validated);
        return response()->json(['success' => 'Payroll record updated.']);
    }

    private function hasDuplicate(Request $request, $ignoreId = null)
    {
        $query = \App\Models\Salary::where('teacher_id', $request->teacher_id)
            ->where('month', $request->month)
            ->where('year', $request->year);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    public function destroy($id)
    {
        $salary = \App\Models\Salary::findOrFail($id);
        $salary->delete();
        return response()->json(['success' => 'Payroll record removed.']);
    }
}
