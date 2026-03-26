<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Branch::with('school')->latest();
            
            if (!auth()->user()->isMasterAdmin()) {
                $query->where('school_id', auth()->user()->school_id);
            } elseif ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }

            $branches = $query->get();
            return response()->json($branches);
        }

        $admins = collect();
        if (auth()->user()->isMasterAdmin()) {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->with('school')->get();
        }

        return view('branches.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        $validated['is_main'] = $request->has('is_main') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // If this is set as main branch, unset other main branches for this school
        if ($validated['is_main']) {
            Branch::where('school_id', $validated['school_id'])
                ->where('is_main', true)
                ->update(['is_main' => false]);
        }

        $branch = Branch::create($validated);

        return response()->json(['success' => __('Branch created successfully.'), 'branch' => $branch]);
    }

    public function show(string $id)
    {
        $branch = Branch::with('school')->findOrFail($id);
        return response()->json($branch);
    }

    public function update(Request $request, string $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_main' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        $validated['is_main'] = $request->has('is_main') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // If this is set as main branch, unset other main branches for this school
        if ($validated['is_main']) {
            Branch::where('school_id', $validated['school_id'])
                ->where('is_main', true)
                ->where('id', '!=', $id)
                ->update(['is_main' => false]);
        }

        $branch->update($validated);

        return response()->json(['success' => __('Branch updated successfully.'), 'branch' => $branch]);
    }

    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        
        // Prevent deletion of main branch
        if ($branch->is_main) {
            return response()->json(['error' => __('Cannot delete the main branch.')], 422);
        }

        $branch->delete();

        return response()->json(['success' => __('Branch deleted successfully.')]);
    }
}
