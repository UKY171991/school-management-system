<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $fees = \App\Models\FeeStructure::latest()->get();
            return response()->json($fees);
        }
        return view('fee-structure.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $fee = \App\Models\FeeStructure::create($validated);
        return response()->json(['success' => 'Fee structure created.', 'fee' => $fee]);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\FeeStructure::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $fee = \App\Models\FeeStructure::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $fee->update($validated);
        return response()->json(['success' => 'Fee structure updated.']);
    }

    public function destroy(string $id)
    {
        $fee = \App\Models\FeeStructure::findOrFail($id);
        $fee->delete();
        return response()->json(['success' => 'Fee structure deleted.']);
    }
}
