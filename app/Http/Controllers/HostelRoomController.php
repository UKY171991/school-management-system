<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HostelRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rooms = \App\Models\HostelRoom::with('hostel')->latest()->get();
            return response()->json($rooms);
        }
        return view('hostel-rooms.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
        ]);

        $room = \App\Models\HostelRoom::create($validated);
        return response()->json(['success' => 'Hostel room added.', 'room' => $room]);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\HostelRoom::with('hostel')->findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $room = \App\Models\HostelRoom::findOrFail($id);
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_number' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
        ]);

        $room->update($validated);
        return response()->json(['success' => 'Hostel room updated.']);
    }

    public function destroy(string $id)
    {
        $room = \App\Models\HostelRoom::findOrFail($id);
        $room->delete();
        return response()->json(['success' => 'Hostel room removed.']);
    }
}
