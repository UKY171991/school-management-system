<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Hostel::with('school')->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $hostels = $query->get();
            return response()->json($hostels);
        }
        $schools = \App\Models\School::all();
        return view('hostels.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Boys,Girls,Common',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $hostel = Hostel::create($validated);

        return response()->json(['success' => 'Hostel added successfully.', 'hostel' => $hostel]);
    }

    public function show($id)
    {
        return response()->json(Hostel::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $hostel = Hostel::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:Boys,Girls,Common',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $hostel->update($validated);

        return response()->json(['success' => 'Hostel updated successfully.']);
    }

    public function destroy($id)
    {
        $hostel = Hostel::findOrFail($id);
        $hostel->delete();

        return response()->json(['success' => 'Hostel removed successfully.']);
    }
}
