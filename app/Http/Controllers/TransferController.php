<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\StudentTransfer::with('student', 'school')->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $transfers = $query->get();
            return response()->json($transfers);
        }
        $schools = \App\Models\School::all();
        return view('transfers.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'student_id' => 'required|exists:students,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string',
            'to_school' => 'nullable|string',
            'lc_number' => 'nullable|string',
        ]);

        $transfer = \App\Models\StudentTransfer::create($validated);

        return response()->json(['success' => 'Transfer record created successfully.', 'transfer' => $transfer]);
    }

    public function show($id)
    {
        $transfer = \App\Models\StudentTransfer::with('student.grade')->findOrFail($id);
        return response()->json($transfer);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'student_id' => 'required|exists:students,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string',
            'to_school' => 'nullable|string',
            'lc_number' => 'nullable|string',
        ]);

        $transfer = \App\Models\StudentTransfer::findOrFail($id);
        $transfer->update($validated);

        return response()->json(['success' => 'Transfer record updated successfully.', 'transfer' => $transfer]);
    }

    public function destroy($id)
    {
        $transfer = \App\Models\StudentTransfer::findOrFail($id);
        $transfer->delete();

        return response()->json(['success' => 'Transfer record deleted successfully.']);
    }
}
