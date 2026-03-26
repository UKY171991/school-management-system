<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $drivers = \App\Models\Driver::latest()->get();
            return response()->json($drivers);
        }
        return view('drivers.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
        ]);

        $driver = \App\Models\Driver::create($validated);
        return response()->json(['success' => 'Driver added.', 'driver' => $driver]);
    }

    public function show($id)
    {
        return response()->json(\App\Models\Driver::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
        ]);

        $driver->update($validated);
        return response()->json(['success' => 'Driver updated.']);
    }

    public function destroy($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $driver->delete();
        return response()->json(['success' => 'Driver removed.']);
    }
}
