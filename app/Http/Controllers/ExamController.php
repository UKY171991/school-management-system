<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Exam::with(['grade', 'school'])->latest();
            
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }

            $exams = $query->get();
            return response()->json($exams);
        }
        $sections = \App\Models\Section::with('grade')->get();
        $subjects = \App\Models\Subject::all();
        $grades = \App\Models\Grade::all();
        $schools = \App\Models\School::all();
        $examTypes = \App\Models\ExamType::all();
        return view('exams.index', compact('sections', 'subjects', 'grades', 'schools', 'examTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'session' => 'nullable|string|max:255',
            'grade_id' => 'nullable|exists:grades,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $exam = \App\Models\Exam::create($validated);

        return response()->json(['success' => 'Exam scheduled successfully.', 'exam' => $exam]);
    }

    public function show($id)
    {
        return response()->json(\App\Models\Exam::with('school')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $exam = \App\Models\Exam::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'session' => 'nullable|string|max:255',
            'grade_id' => 'nullable|exists:grades,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $exam->update($validated);

        return response()->json(['success' => 'Exam updated successfully.']);
    }

    public function destroy($id)
    {
        $exam = \App\Models\Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['success' => 'Exam cancelled successfully.']);
    }

    public function generateTimetable(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'section_id' => 'required|exists:sections,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'start_date' => 'required|date',
            'room_number' => 'nullable|string|max:255',
        ]);

        $examId = $request->exam_id;
        $sectionId = $request->section_id;
        $roomNumber = $request->room_number ?? 'TBD';
        $currentDate = \Carbon\Carbon::parse($request->start_date);

        // Remove existing entries for this exam and section to prevent duplicates
        \App\Models\ExamTimetable::where('exam_id', $examId)
            ->where('section_id', $sectionId)
            ->delete();
        
        foreach ($request->subject_ids as $subjectId) {
            // Skip Sundays
            if ($currentDate->isSunday()) {
                $currentDate->addDay();
            }

            \App\Models\ExamTimetable::create([
                'exam_id' => $examId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'exam_date' => $currentDate->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'room_number' => $roomNumber
            ]);

            $currentDate->addDay();
        }

        return response()->json(['success' => 'Timetable generated successfully for selected subjects.']);
    }
}
