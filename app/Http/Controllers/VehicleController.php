<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Vehicle::with('school')->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $vehicles = $query->get();
            return response()->json($vehicles);
        }
        $schools = \App\Models\School::all();
        return view('vehicles.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number',
            'capacity' => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json(['success' => 'Vehicle added successfully.', 'vehicle' => $vehicle]);
    }

    public function show($id)
    {
        return response()->json(Vehicle::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'vehicle_number' => 'required|string|unique:vehicles,vehicle_number,' . $id,
            'capacity' => 'required|integer|min:1',
        ]);

        $vehicle->update($validated);

        return response()->json(['success' => 'Vehicle updated successfully.']);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return response()->json(['success' => 'Vehicle removed successfully.']);
    }
}
