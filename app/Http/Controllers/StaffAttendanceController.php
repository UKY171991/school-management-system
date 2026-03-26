<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $leaves = \App\Models\StaffLeave::with('teacher')->latest()->get();
            return response()->json($leaves);
        }
        $teachers = \App\Models\Teacher::all();
        return view('staff-attendance.index', compact('teachers'));
    }

    public function show($id)
    {
        return response()->json(\App\Models\StaffLeave::with('teacher')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $leave = \App\Models\StaffLeave::create($validated);

        return response()->json(['success' => 'Leave request submitted.', 'leave' => $leave]);
    }

    public function update(Request $request, string $id)
    {
        $leave = \App\Models\StaffLeave::findOrFail($id);

        if ($request->has('status') && $request->keys() == ['status']) {
             $leave->update(['status' => $request->status]);
             return response()->json(['success' => 'Leave status updated.']);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $leave->update($validated);
        return response()->json(['success' => 'Leave request updated.']);
    }

    public function destroy(string $id)
    {
        $leave = \App\Models\StaffLeave::findOrFail($id);
        $leave->delete();

        return response()->json(['success' => 'Leave record removed.']);
    }
}
