<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Syllabus::with(['subject', 'section.grade', 'school'])->latest();
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $syllabi = $query->get();
            return response()->json($syllabi);
        }
        $schools = \App\Models\School::all();
        return view('syllabus.index', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:255',
            'syllabus_file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $file = $request->file('syllabus_file');
        $filename = time() . '_syllabus.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/syllabi'), $filename);
        $path = 'syllabi/' . $filename;

        $syllabus = \App\Models\Syllabus::create([
            'school_id' => $request->school_id,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'title' => $request->title,
            'file_path' => $path,
        ]);

        return response()->json(['success' => 'Syllabus uploaded successfully.', 'syllabus' => $syllabus]);
    }

    public function destroy($id)
    {
        $syllabus = \App\Models\Syllabus::findOrFail($id);
        if ($syllabus->file_path && file_exists(public_path('storage/' . $syllabus->file_path))) {
            @unlink(public_path('storage/' . $syllabus->file_path));
        }
        $syllabus->delete();

        return response()->json(['success' => 'Syllabus deleted successfully.']);
    }
}
