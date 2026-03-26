<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Student::with(['grade', 'school'])->latest();
            
            if ($request->has('school_id') && !empty($request->school_id)) {
                $query->where('school_id', $request->school_id);
            }
            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('grade_id', $request->grade_id);
            }

            $students = $query->get();
            return response()->json($students);
        }
        $schools = \App\Models\School::all();
        $grades = \App\Models\Grade::with('school')->get();
        $exams = \App\Models\Exam::with('school')->get();
        return view('report-cards.index', compact('schools', 'grades', 'exams'));
    }

    public function show($id, Request $request)
    {
        $exam_type_id = $request->exam_id;
        $student = \App\Models\Student::with(['grade', 'school'])->findOrFail($id);
        $marks = \App\Models\Mark::where('student_id', $id)
            ->where('exam_type_id', $exam_type_id)
            ->with('subject')
            ->get();
        
        $exam = \App\Models\ExamType::with('school')->findOrFail($exam_type_id);


        $totalObtained = $marks->sum('marks_obtained');
        $totalMax = $marks->sum(function($m) {
            return $m->max_marks ? (float)$m->max_marks : 100;
        });

        return response()->json([
            'student' => $student,
            'exam' => $exam,
            'marks' => $marks,
            'total_marks' => $totalObtained,
            'max_total' => $totalMax,
            'percentage' => $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0
        ]);
    }
}
