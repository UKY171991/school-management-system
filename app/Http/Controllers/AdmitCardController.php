<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\School;
use App\Models\ExamTimetable;
use App\Models\Section;

class AdmitCardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Student::with(['grade', 'school', 'section'])->latest();
            
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }
            if ($request->has('section_id') && !empty($request->section_id)) {
                $query->where('section_id', $request->section_id);
            }

            $students = $query->get();
            return response()->json($students);
        }

        $schools = School::all();
        $grades = Grade::with('school')->get();
        $sections = Section::with('school')->get();
        $exams = Exam::with('school')->get();
        
        return view('admit-cards.index', compact('schools', 'grades', 'sections', 'exams'));
    }

    public function show($id, Request $request)
    {
        $exam_id = $request->exam_id;
        $student = Student::with(['grade', 'school', 'section'])->findOrFail($id);
        $exam = Exam::with('school')->findOrFail($exam_id);

        // Fetch timetable for this exam and the student's section
        $timetable = ExamTimetable::where('exam_id', $exam_id)
            ->where('section_id', $student->section_id)
            ->with('subject')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'student' => $student,
            'exam' => $exam,
            'timetable' => $timetable
        ]);
    }
}
