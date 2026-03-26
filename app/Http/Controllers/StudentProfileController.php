<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Student::with('grade', 'school', 'documents')->latest();
            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $students = $query->get();
            return response()->json($students);
        }
        $schools = \App\Models\School::all();
        return view('student-profiles.index', compact('schools'));
    }

    public function show(string $id)
    {
        $student = \App\Models\Student::with('grade', 'documents')->findOrFail($id);
        return response()->json($student);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'doc_name' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,png,doc,docx|max:5120',
        ]);

        $file = $request->file('document');
        $filename = time() . '_doc.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/student_docs'), $filename);
        $path = 'student_docs/' . $filename;

        $doc = \App\Models\StudentDocument::create([
            'student_id' => $request->student_id,
            'name' => $request->doc_name,
            'path' => $path,
        ]);

        return response()->json(['success' => 'Document uploaded successfully.', 'document' => $doc]);
    }

    public function destroy($id)
    {
        $doc = \App\Models\StudentDocument::findOrFail($id);
        if ($doc->path && file_exists(public_path('storage/' . $doc->path))) {
            @unlink(public_path('storage/' . $doc->path));
        }
        $doc->delete();

        return response()->json(['success' => 'Document deleted successfully.']);
    }
}
