<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Teacher::with('school')->latest();
            if (auth()->user()->isMasterAdmin() && $request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $teachers = $query->get();
            return response()->json($teachers);
        }
        
        $admins = collect();
        if (auth()->user()->isMasterAdmin()) {
            $admins = \App\Models\User::whereHas('role', function($q) {
                $q->where('slug', 'admin');
            })->with('school')->get();
        }
        
        return view('teacher-profiles.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_photo.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/teachers/photos'), $filename);
            $validated['photo'] = 'teachers/photos/' . $filename;
        }

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $filename = time() . '_sig.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/teachers/signatures'), $filename);
            $validated['signature'] = 'teachers/signatures/' . $filename;
        }

        $teacher = Teacher::create($validated);

        return response()->json(['success' => __('Teacher profile created.'), 'teacher' => $teacher]);
    }

    public function show(string $id)
    {
        $teacher = Teacher::with('school')->findOrFail($id);
        return response()->json($teacher);
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $validated = $request->validate([
            'school_id' => auth()->user()->isMasterAdmin() ? 'required|exists:schools,id' : 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,'.$teacher->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp|max:2048',
        ]);

        if (!auth()->user()->isMasterAdmin()) {
            $validated['school_id'] = auth()->user()->school_id;
        }

        if ($request->hasFile('photo')) {
            if ($teacher->photo && file_exists(public_path('storage/' . $teacher->photo))) {
                @unlink(public_path('storage/' . $teacher->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_photo.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/teachers/photos'), $filename);
            $validated['photo'] = 'teachers/photos/' . $filename;
        }

        if ($request->hasFile('signature')) {
            if ($teacher->signature && file_exists(public_path('storage/' . $teacher->signature))) {
                @unlink(public_path('storage/' . $teacher->signature));
            }
            $file = $request->file('signature');
            $filename = time() . '_sig.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/teachers/signatures'), $filename);
            $validated['signature'] = 'teachers/signatures/' . $filename;
        }

        $teacher->update($validated);

        return response()->json(['success' => __('Teacher profile updated.'), 'teacher' => $teacher]);
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        if ($teacher->photo && file_exists(public_path('storage/' . $teacher->photo))) {
            @unlink(public_path('storage/' . $teacher->photo));
        }
        if ($teacher->signature && file_exists(public_path('storage/' . $teacher->signature))) {
            @unlink(public_path('storage/' . $teacher->signature));
        }

        $teacher->delete();

        return response()->json(['success' => __('Teacher profile deleted.')]);
    }

    public function deleteAsset(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $type = $request->type; // 'photo' or 'signature'
        
        if ($type == 'photo' && $teacher->photo) {
            if (file_exists(public_path('storage/' . $teacher->photo))) {
                @unlink(public_path('storage/' . $teacher->photo));
            }
            $teacher->photo = null;
        } elseif ($type == 'signature' && $teacher->signature) {
            if (file_exists(public_path('storage/' . $teacher->signature))) {
                @unlink(public_path('storage/' . $teacher->signature));
            }
            $teacher->signature = null;
        }
        
        $teacher->save();
        
        return response()->json(['success' => __(ucfirst($type)) . ' ' . __('deleted successfully.')]);
    }
}
