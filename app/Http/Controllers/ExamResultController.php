<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    /**
     * Display a listing of exam results.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $exam_id = $request->exam_id;
            $school_id = $request->school_id;
            $grade_id = $request->grade_id;
            
            // If no exam selected, return empty result
            if (!$exam_id) {
                return response()->json([]);
            }
            
            $query = \App\Models\Student::with(['grade.teacher', 'school'])
                ->whereHas('marks', function($q) use ($exam_id) {
                    $q->where('exam_type_id', $exam_id);
                });
            
            if ($school_id) {
                $query->where('school_id', $school_id);
            }
            
            if ($grade_id) {
                $query->where('grade_id', $grade_id);
            }
            
            $students = $query->orderBy('roll_number')->get();
            
            // Calculate class positions
            $allResults = [];
            foreach ($students as $student) {
                $marks = \App\Models\Mark::where('student_id', $student->id)
                    ->where('exam_type_id', $exam_id)
                    ->with('subject')
                    ->get();
                
                $totalObtained = $marks->sum('marks_obtained');
                $totalMax = $marks->sum(function($m) {
                    return $m->max_marks ? (float)$m->max_marks : 100;
                });
                
                $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
                $status = $percentage >= 40 ? 'PASSED' : 'FAILED';
                
                $allResults[] = [
                    'student_id' => $student->id,
                    'percentage' => $percentage,
                    'total_marks' => $totalObtained
                ];
            }
            
            // Sort by percentage descending to determine positions
            usort($allResults, function($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });
            
            // Assign positions
            $positions = [];
            $currentPosition = 1;
            for ($i = 0; $i < count($allResults); $i++) {
                if ($i > 0 && $allResults[$i]['percentage'] < $allResults[$i-1]['percentage']) {
                    $currentPosition = $i + 1;
                }
                $positions[$allResults[$i]['student_id']] = $currentPosition;
            }
            
            $results = $students->map(function($student) use ($exam_id, $positions) {
                $marks = \App\Models\Mark::where('student_id', $student->id)
                    ->where('exam_type_id', $exam_id)
                    ->with('subject')
                    ->get();
                
                $totalObtained = $marks->sum('marks_obtained');
                $totalMax = $marks->sum(function($m) {
                    return $m->max_marks ? (float)$m->max_marks : 100;
                });
                
                $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
                $status = $percentage >= 40 ? 'PASSED' : 'FAILED';
                
                return [
                    'id' => $student->id,
                    'roll_number' => $student->roll_number,
                    'name' => $student->name,
                    'school' => $student->school,
                    'grade' => $student->grade,
                    'total_marks' => $totalObtained,
                    'max_marks' => $totalMax,
                    'percentage' => round($percentage, 2),
                    'status' => $status,
                    'position' => $positions[$student->id] ?? '-',
                    'subject_count' => $marks->count()
                ];
            });
            
            return response()->json($results);
        }
        
        $schools = \App\Models\School::all();
        $grades = \App\Models\Grade::all();
        $exams = \App\Models\ExamType::with('school')->get();
        
        return view('exam-results.index', compact('schools', 'grades', 'exams'));
    }
    
    /**
     * Get exams based on school selection.
     */
    public function getExamsBySchool(Request $request)
    {
        $school_id = $request->school_id;
        
        $query = \App\Models\ExamType::orderBy('name', 'asc');
        
        if ($school_id) {
            $query->where('school_id', $school_id);
        }
        
        $exams = $query->get();
        
        return response()->json($exams);
    }

    /**
     * Get grades based on school selection.
     */
    public function getGradesBySchool(Request $request)
    {
        $school_id = $request->school_id;
        
        $query = \App\Models\Grade::orderBy('name', 'asc');
        
        if ($school_id) {
            $query->where('school_id', $school_id);
        }
        
        $grades = $query->get();
        
        return response()->json($grades);
    }

    /**
     * Get all exam results for a specific student.
     */
    public function getStudentResults($id)
    {
        $student = \App\Models\Student::with(['grade.teacher', 'section', 'school'])->findOrFail($id);
        
        $marks = \App\Models\Mark::where('student_id', $id)
            ->with(['examType', 'subject'])
            ->get();
            
        $groupedMarks = $marks->groupBy('exam_type_id');
        
        $results = [];
        foreach ($groupedMarks as $examId => $examMarks) {
            $exam = $examMarks->first()->examType;
            if (!$exam) continue;

            $totalObtained = $examMarks->sum('marks_obtained');
            $totalMax = $examMarks->sum(function($m) {
                return $m->max_marks ? (float)$m->max_marks : 100;
            });
            
            $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
            
            $results[] = [
                'exam_id' => $examId,
                'exam_name' => $exam->name,
                'total_marks' => $totalObtained,
                'max_marks' => $totalMax,
                'percentage' => round($percentage, 2),
                'status' => $percentage >= 40 ? 'PASSED' : 'FAILED',
                'marks' => $examMarks
            ];
        }
        
        return response()->json([
            'student' => $student,
            'results' => $results
        ]);
    }

    /**
     * Print result for a student and specific exam.
     */
    public function printResult(Request $request, $id)
    {
        $exam_id = $request->exam_id;
        $student = \App\Models\Student::with(['grade.teacher', 'section', 'school'])->findOrFail($id);
        
        $query = \App\Models\Mark::where('student_id', $id)->with(['examType', 'subject']);
        
        if ($exam_id) {
            $query->where('exam_type_id', $exam_id);
            $exam = \App\Models\ExamType::findOrFail($exam_id);
        } else {
            // If no exam_id, maybe get the latest one
            $latestMark = \App\Models\Mark::where('student_id', $id)->latest()->first();
            if ($latestMark) {
                $exam_id = $latestMark->exam_type_id;
                $query->where('exam_type_id', $exam_id);
                $exam = \App\Models\ExamType::findOrFail($exam_id);
            } else {
                return back()->with('error', 'No marks found for this student.');
            }
        }
        
        $marks = $query->get();
        
        $totalObtained = $marks->sum('marks_obtained');
        $totalMax = $marks->sum(function($m) {
            return $m->max_marks ? (float)$m->max_marks : 100;
        });
        
        $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
        
        // Calculate class position
        $position = $this->calculatePosition($student->grade_id, $exam_id, $id);
        
        return view('exam-results.print', compact('student', 'exam', 'marks', 'totalObtained', 'totalMax', 'percentage', 'position'));
    }

    /**
     * Print full consolidated result for all exams.
     */
    public function printFullResult($id)
    {
        $student = \App\Models\Student::with(['grade.teacher', 'section', 'school'])->findOrFail($id);
        
        $allMarks = \App\Models\Mark::where('student_id', $id)
            ->with(['examType', 'subject'])
            ->get();
            
        // Get unique exams and subjects
        $allExams = $allMarks->pluck('examType')->unique('id')->sortBy('id');
        $subjects = $allMarks->pluck('subject')->unique('id')->sortBy('name');
        
        $marksMatrix = [];
        foreach ($allMarks as $mark) {
            $marksMatrix[$mark->subject_id][$mark->exam_type_id] = $mark;
        }

        $examSummaries = [];
        foreach ($allExams as $exam) {
            $examMarks = $allMarks->where('exam_type_id', $exam->id);
            $totalObtained = $examMarks->sum('marks_obtained');
            $totalMax = $examMarks->sum(function($m) {
                return $m->max_marks ?: 100;
            });
            $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
            $position = $this->calculatePosition($student->grade_id, $exam->id, $student->id);
            
            $examSummaries[$exam->id] = [
                'total_obtained' => $totalObtained,
                'total_max' => $totalMax,
                'percentage' => round($percentage, 2),
                'position' => $position
            ];
        }

        $grandTotalObtained = $allMarks->sum('marks_obtained');
        $grandTotalMax = $allMarks->sum(function($m) {
            return $m->max_marks ?: 100;
        });
        $overallPercentage = $grandTotalMax > 0 ? ($grandTotalObtained / $grandTotalMax) * 100 : 0;

        // Calculate Overall Class Position (Cumulative across all exams appearing in this report)
        $examIds = $allExams->pluck('id')->toArray();
        $overallPosition = $this->calculateOverallPosition($student->grade_id, $student->id, $examIds);

        return view('exam-results.full-print', compact(
            'student', 
            'subjects', 
            'allExams', 
            'marksMatrix', 
            'examSummaries', 
            'grandTotalObtained', 
            'grandTotalMax', 
            'overallPercentage',
            'overallPosition'
        ));
    }

    /**
     * Helper to calculate overall class position across all exams.
     */
    private function calculateOverallPosition($grade_id, $student_id, $examIds)
    {
        if (!$grade_id || empty($examIds)) return '-';

        $studentsInClass = \App\Models\Student::where('grade_id', $grade_id)->get();
        $studentIds = $studentsInClass->pluck('id')->toArray();

        // Get all marks for these students and exams in one query
        $allClassMarks = \App\Models\Mark::whereIn('student_id', $studentIds)
            ->whereIn('exam_type_id', $examIds)
            ->get()
            ->groupBy('student_id');

        $rankings = [];

        foreach ($studentsInClass as $s) {
            $sMarks = $allClassMarks->get($s->id);
            if (!$sMarks || $sMarks->count() == 0) continue;

            $totalObtained = $sMarks->sum('marks_obtained');
            
            // We rank by total marks obtained for overall position
            $rankings[] = [
                'student_id' => $s->id,
                'total_obtained' => $totalObtained
            ];
        }

        // Sort rankings by total obtained descending
        usort($rankings, function($a, $b) {
            return $b['total_obtained'] <=> $a['total_obtained'];
        });

        $position = '-';
        $currentRank = 1;
        for ($i = 0; $i < count($rankings); $i++) {
            if ($i > 0 && $rankings[$i]['total_obtained'] < $rankings[$i-1]['total_obtained']) {
                $currentRank = $i + 1;
            }
            if ($rankings[$i]['student_id'] == $student_id) {
                $position = $currentRank;
                break;
            }
        }

        return $position;
    }

    /**
     * Helper to calculate class position for a student in a specific exam.
     */
    private function calculatePosition($grade_id, $exam_id, $student_id)
    {
        if (!$grade_id || !$exam_id) return '-';

        $studentsInClass = \App\Models\Student::where('grade_id', $grade_id)->get();
        $rankings = [];

        foreach ($studentsInClass as $s) {
            $sMarks = \App\Models\Mark::where('student_id', $s->id)
                ->where('exam_type_id', $exam_id)
                ->get();
            
            if ($sMarks->count() == 0) continue;

            $sTotalObtained = $sMarks->sum('marks_obtained');
            $sTotalMax = $sMarks->sum(function($m) {
                return $m->max_marks ? (float)$m->max_marks : 100;
            });
            $sPercentage = $sTotalMax > 0 ? ($sTotalObtained / $sTotalMax) * 100 : 0;

            $rankings[] = [
                'student_id' => $s->id,
                'percentage' => $sPercentage
            ];
        }

        // Sort rankings by percentage descending
        usort($rankings, function($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        // Determine position (handling ties)
        $position = '-';
        $currentRank = 1;
        for ($i = 0; $i < count($rankings); $i++) {
            if ($i > 0 && $rankings[$i]['percentage'] < $rankings[$i-1]['percentage']) {
                $currentRank = $i + 1;
            }
            if ($rankings[$i]['student_id'] == $student_id) {
                $position = $currentRank;
                break;
            }
        }

        return $position;
    }

    /**
     * Show detailed results for a specific student.
     */
    public function show($id, Request $request)
    {
        $exam_id = $request->exam_id;
        
        $student = \App\Models\Student::with(['grade.teacher', 'section', 'school'])->findOrFail($id);
        $marks = \App\Models\Mark::where('student_id', $id)
            ->where('exam_type_id', $exam_id)
            ->with('subject')
            ->get();
        
        $exam = \App\Models\ExamType::with('school')->findOrFail($exam_id);
        
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
    
    /**
     * Get exam statistics.
     */
    public function statistics(Request $request)
    {
        $exam_id = $request->exam_id;
        $school_id = $request->school_id;
        $grade_id = $request->grade_id;
        
        // If no exam selected, return empty result
        if (!$exam_id) {
            return response()->json([
                'total_students' => 0,
                'passed_students' => 0,
                'failed_students' => 0,
                'pass_percentage' => 0,
                'average_percentage' => 0
            ]);
        }
        
        $query = \App\Models\Student::whereHas('marks', function($q) use ($exam_id) {
            $q->where('exam_type_id', $exam_id);
        });
        
        if ($school_id) {
            $query->where('school_id', $school_id);
        }
        
        if ($grade_id) {
            $query->where('grade_id', $grade_id);
        }
        
        $students = $query->get();
        
        $totalStudents = $students->count();
        $passedStudents = 0;
        $failedStudents = 0;
        $totalPercentage = 0;
        
        foreach ($students as $student) {
            $marks = \App\Models\Mark::where('student_id', $student->id)
                ->where('exam_type_id', $exam_id)
                ->get();
            
            $totalObtained = $marks->sum('marks_obtained');
            $totalMax = $marks->sum(function($m) {
                return $m->max_marks ? (float)$m->max_marks : 100;
            });
            
            $percentage = $totalMax > 0 ? ($totalObtained / $totalMax) * 100 : 0;
            $totalPercentage += $percentage;
            
            if ($percentage >= 40) {
                $passedStudents++;
            } else {
                $failedStudents++;
            }
        }
        
        $averagePercentage = $totalStudents > 0 ? $totalPercentage / $totalStudents : 0;
        $passPercentage = $totalStudents > 0 ? ($passedStudents / $totalStudents) * 100 : 0;
        
        return response()->json([
            'total_students' => $totalStudents,
            'passed_students' => $passedStudents,
            'failed_students' => $failedStudents,
            'pass_percentage' => round($passPercentage, 2),
            'average_percentage' => round($averagePercentage, 2)
        ]);
    }
}
