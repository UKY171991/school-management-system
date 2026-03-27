<?php

namespace App\Http\Controllers;

use App\Models\TimetableSlot;
use Illuminate\Http\Request;

class TimetableSlotController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->isMasterAdmin() ? $request->get('school_id') : $user->school_id;

        $query = TimetableSlot::orderBy('sort_order', 'asc');
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
            'is_break'   => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $user = auth()->user();
        $validated['school_id'] = $user->isMasterAdmin()
            ? $request->get('school_id')
            : $user->school_id;

        // Default is_break to false if not provided
        $validated['is_break'] = $request->boolean('is_break');

        $slot = TimetableSlot::create($validated);
        return response()->json(['success' => 'Slot created successfully', 'slot' => $slot]);
    }

    public function show($id)
    {
        return response()->json(TimetableSlot::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $slot = TimetableSlot::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
            'is_break'   => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_break'] = $request->boolean('is_break');

        $slot->update($validated);
        return response()->json(['success' => 'Slot updated successfully', 'slot' => $slot]);
    }

    public function destroy($id)
    {
        $slot = TimetableSlot::findOrFail($id);
        $slot->delete();
        return response()->json(['success' => 'Slot deleted successfully']);
    }
}
