<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['school', 'role']);
            
            if (!auth()->user()->isMasterAdmin()) {
                $query->where('school_id', auth()->user()->school_id);
            } elseif ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            
            $query->orderBy('name', 'asc');
            $users = $query->get()->map(function($u) {
                if ($u->role) {
                    $u->role->name = __($u->role->name);
                }
                return $u;
            });
            return response()->json($users);
        }
        if (auth()->user()->isMasterAdmin()) {
            $schools = School::all();
        } else {
            $schools = School::where('id', auth()->user()->school_id)->get();
        }
        if (auth()->user()->isMasterAdmin()) {
            $roles = Role::all();
        } else {
            $roles = Role::where('slug', '!=', 'master-admin')->get();
        }
        return view('users.index', compact('schools', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'nullable|exists:schools,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Permission Check: Admin can create any role EXCEPT master-admin. Only Master Admin can create/assign Master Admin.
        $role = Role::find($validated['role_id']);
        if ($role->slug === 'master-admin' && !auth()->user()->isMasterAdmin()) {
            return response()->json(['error' => __('You do not have permission to assign the Master Admin role.')], 403);
        }

        $validated['password'] = Hash::make($validated['password']);

        // Force school_id for non-master admins
        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        $user = User::create($validated);

        return response()->json(['success' => __('User created successfully.'), 'user' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['school'])->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'school_id' => 'nullable|exists:schools,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Permission Check: Only Master Admin can assign Master Admin role
        $role = Role::find($validated['role_id']);
        if ($role->slug === 'master-admin' && !auth()->user()->isMasterAdmin()) {
            if ($user->role->slug !== 'master-admin') { // Prevent promoting to Master Admin
                return response()->json(['error' => __('You do not have permission to assign the Master Admin role.')], 403);
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Force school_id for non-master admins
        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        $user->update($validated);

        return response()->json(['success' => __('User updated successfully.'), 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the currently authenticated user
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete yourself.'], 422);
        }

        $user->delete();

        return response()->json(['success' => __('User deleted successfully.')]);
    }
}
