<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamTimetable;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Subject;

class ExamTimetableController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ExamTimetable::with(['exam.school', 'section.grade', 'subject'])->latest();

            if ($request->has('exam_id') && !empty($request->exam_id)) {
                $query->where('exam_id', $request->exam_id);
            }

            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->whereHas('exam', function($q) use ($request) {
                    $q->where('school_id', $request->school_id);
                });
            }

            if ($request->has('section_id') && !empty($request->section_id)) {
                $query->where('section_id', $request->section_id);
            } elseif ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->whereHas('section', function($q) use ($request) {
                    $q->where('grade_id', $request->grade_id);
                });
            }

            if ($request->has('exam_type') && !empty($request->exam_type)) {
                $query->whereHas('exam', function($q) use ($request) {
                    $q->where('type', $request->exam_type);
                });
            }

            return response()->json($query->get());
        }

        // For non-ajax (printing or initial load)
        if ($request->has('print')) {
            $exam_id = $request->exam_id;
            $exam_type = $request->exam_type; // Support filtering by type
            $section_id = $request->section_id;
            $school_id = $request->school_id;
            $grade_id = $request->grade_id;

            $query = ExamTimetable::with(['exam.school', 'section.grade', 'subject'])
                ->orderBy('exam_date')
                ->orderBy('start_time');

            if ($section_id) {
                $query->where('section_id', $section_id);
            } elseif ($grade_id) {
                // If specific section not selected but grade is
                $query->whereHas('section', function($q) use ($grade_id) {
                    $q->where('grade_id', $grade_id);
                });
            }

            if ($school_id) {
                 $query->whereHas('exam', function($q) use ($school_id) {
                    $q->where('school_id', $school_id);
                });
            }

            if ($exam_id) {
                $query->where('exam_id', $exam_id);
                $exam = Exam::find($exam_id);
            } elseif ($exam_type) {
                $query->whereHas('exam', function($q) use ($exam_type) {
                    $q->where('type', $exam_type);
                });
                // Attempt to find a representative exam for the header
                $exam = Exam::where('type', $exam_type)->when($school_id, function($q) use ($school_id){
                    $q->where('school_id', $school_id);
                })->latest()->first(); 
            }

            $timetables = $query->get();
            $section = $section_id ? Section::with('grade')->find($section_id) : null;

            // If we didn't find a direct exam object (e.g. no timetables found), create a dummy or handle gracefully
            if (!isset($exam) || !$exam) {
                $exam = new Exam();
                $exam->name = $exam_type ?? 'Exam Schedule';
            }

            return view('exam-timetable.print', compact('timetables', 'exam', 'section'));
        }

        $exams = Exam::all();
        $sections = Section::with('grade')->get();
        $grades = \App\Models\Grade::all();
        $subjects = Subject::all();
        $schools = \App\Models\School::all();
        $examTypes = \App\Models\ExamType::all();

        return view('exam-timetable.index', compact('exams', 'sections', 'grades', 'subjects', 'schools', 'examTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_type' => 'required|string', // Changed from exam_id
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_number' => 'nullable|string|max:255',
        ]);

        // Resolve Exam ID from Type
        $section = Section::with('grade')->find($request->section_id);
        $schoolId = $section->grade->school_id;

        $exam = Exam::firstOrCreate(
            ['school_id' => $schoolId, 'type' => $request->exam_type],
            ['name' => $request->exam_type, 'session' => date('Y')]
        );

        $validated['exam_id'] = $exam->id;
        unset($validated['exam_type']);

        $timetable = ExamTimetable::create($validated);
        return response()->json(['success' => 'Exam timetable entry added successfully.', 'timetable' => $timetable]);
    }

    public function show($id)
    {
        return response()->json(ExamTimetable::with(['exam', 'section.grade', 'subject'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $timetable = ExamTimetable::findOrFail($id);
        $validated = $request->validate([
            'exam_type' => 'required|string', // Changed from exam_id
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_number' => 'nullable|string|max:255',
        ]);

        // Resolve Exam ID from Type
        $section = Section::with('grade')->find($request->section_id);
        $schoolId = $section->grade->school_id;

        $exam = Exam::firstOrCreate(
            ['school_id' => $schoolId, 'type' => $request->exam_type],
            ['name' => $request->exam_type, 'session' => date('Y')]
        );

        $validated['exam_id'] = $exam->id;
        unset($validated['exam_type']);

        $timetable->update($validated);
        return response()->json(['success' => 'Exam timetable entry updated successfully.']);
    }

    public function destroy($id)
    {
        $timetable = ExamTimetable::findOrFail($id);
        $timetable->delete();
        return response()->json(['success' => 'Exam timetable entry removed.']);
    }
}
