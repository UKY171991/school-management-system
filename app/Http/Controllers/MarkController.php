<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->has('fetch_students')) {
                $section_id = $request->section_id;
                $exam_id = $request->exam_type_id;
                $subject_id = $request->subject_id;


                $students = \App\Models\Student::where('section_id', $section_id)
                    ->orderBy('roll_number')
                    ->get();

                $marks = \App\Models\Mark::where('exam_type_id', $exam_id)
                    ->where('subject_id', $subject_id)
                    ->get()
                    ->keyBy('student_id');


                $data = $students->map(function($student) use ($marks) {
                    $mark = $marks->get($student->id);
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'roll_number' => $student->roll_number,
                        'marks_obtained' => $mark ? $mark->marks_obtained : '',
                        'is_absent' => $mark ? ($mark->marks_obtained === null) : false,
                        'max_marks' => $mark ? $mark->max_marks : 100,
                        'remarks' => $mark ? $mark->remarks : '',
                    ];
                });

                return response()->json($data);
            }

            $query = \App\Models\Mark::with(['student', 'examType', 'subject', 'school'])->latest();

            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            $marks = $query->get();
            return response()->json($marks);
        }
        
        $schools = \App\Models\School::all();
        $sections = \App\Models\Section::with('grade')->get();
        $exams = \App\Models\Exam::all();
        $subjects = \App\Models\Subject::all();
        
        return view('marks.index', compact('schools', 'sections', 'exams', 'subjects'));
    }

    public function store(Request $request)
    {
        if ($request->has('bulk')) {
            $school_id = $request->school_id;
            $exam_id = $request->exam_type_id;
            $subject_id = $request->subject_id;

            $max_marks = $request->max_marks ?? 100;
            $marks_data = $request->marks; // array of student_id => marks_obtained

            foreach ($marks_data as $student_id => $marks_obtained) {
                // If marks_obtained is null (Absent) or has a value, we record it.
                // If it's an empty string and not absent, we skip it.
                if ($marks_obtained === null || $marks_obtained !== '') {
                    \App\Models\Mark::updateOrCreate(
                        ['student_id' => $student_id, 'exam_type_id' => $exam_id, 'subject_id' => $subject_id],
                        [
                            'school_id' => $school_id,
                            'marks_obtained' => $marks_obtained, 
                            'max_marks' => $max_marks,
                            'remarks' => $request->remarks[$student_id] ?? null
                        ]
                    );

                }
            }
            return response()->json(['success' => 'Bulk marks saved successfully.']);

        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'student_id' => 'required|exists:students,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'max_marks' => 'required|numeric|min:1',
            'remarks' => 'nullable|string',
        ]);

        $mark = \App\Models\Mark::updateOrCreate(
            ['student_id' => $request->student_id, 'exam_type_id' => $request->exam_type_id, 'subject_id' => $request->subject_id],


            [
                'school_id' => $request->school_id,
                'marks_obtained' => $request->marks_obtained, 
                'max_marks' => $request->max_marks,
                'remarks' => $request->remarks
            ]
        );

        return response()->json(['success' => 'Marks recorded successfully.', 'mark' => $mark]);
    }


    public function show($id)
    {
        return response()->json(\App\Models\Mark::findOrFail($id));
    }

    public function destroy($id)
    {
        $mark = \App\Models\Mark::findOrFail($id);
        $mark->delete();
        return response()->json(['success' => 'Marks entry removed.']);
    }
}
