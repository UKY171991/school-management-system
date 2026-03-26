<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransportRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\TransportRoute::with(['vehicle', 'driver', 'school'])->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $routes = $query->get();
            return response()->json($routes);
        }
        $schools = \App\Models\School::all();
        return view('transport-routes.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $route = \App\Models\TransportRoute::create($validated);
        return response()->json(['success' => 'Transport route created.', 'route' => $route]);
    }

    public function show($id)
    {
        return response()->json(\App\Models\TransportRoute::with('vehicle', 'driver')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $route = \App\Models\TransportRoute::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $route->update($validated);
        return response()->json(['success' => 'Transport route updated.']);
    }

    public function destroy($id)
    {
        $route = \App\Models\TransportRoute::findOrFail($id);
        $route->delete();
        return response()->json(['success' => 'Transport route deleted.']);
    }
}
