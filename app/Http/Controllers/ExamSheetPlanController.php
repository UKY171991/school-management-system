<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Grade;
use App\Models\ExamType;
use App\Models\Student;

class ExamSheetPlanController extends Controller
{
    public function index()
    {
        $schools = School::all();
        // Just empty collections for initial load, handled via AJAX or strict selection usually
        // But for simplicity, we provide schools. 
        // Dependencies can be loaded via same API as print-menu if needed.
        return view('exams.sheet_plan_index', compact('schools'));
    }

    public function generate(Request $request)
    {
        // Filter out empty values from grade_ids
        if ($request->has('grade_ids')) {
            $request->merge([
                'grade_ids' => array_filter($request->grade_ids, function($value) {
                    return !is_null($value) && $value !== '';
                })
            ]);
        }

        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'room_no' => 'required|string',
            'grade_ids' => 'required|array|min:1|max:3',
            'grade_ids.*' => 'exists:grades,id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'nullable|exists:sections,id',
            'start_roll_numbers' => 'nullable|array',
            'start_roll_numbers.*' => 'nullable|numeric|min:1',
        ]);

        $school = School::findOrFail($request->school_id);
        $roomNo = $request->room_no;
        
        $selectedGrades = Grade::whereIn('id', $request->grade_ids)->get();
        // Since whereIn doesn't preserve order, and we need order for mapping sections/rolls,
        // we should iterate over the input `grade_ids` array instead of the collection `selectedGrades`.
        
        $studentsByGrade = [];
        $maxCount = 0;
        
        // Use a map for easy lookup of grade objects if needed, but primarily we need the ID from the input
        $gradesMap = $selectedGrades->keyBy('id');

        foreach ($request->grade_ids as $index => $gradeId) {
            $grade = $gradesMap->get($gradeId);
            if (!$grade) continue;

            $query = Student::where('grade_id', $gradeId);

            // Apply Section Filter
            if (isset($request->section_ids[$index]) && !empty($request->section_ids[$index])) {
                $query->where('section_id', $request->section_ids[$index]);
            }

            // Apply Start Roll Number Filter
            if (isset($request->start_roll_numbers[$index]) && !empty($request->start_roll_numbers[$index])) {
                $query->where('roll_number', '>=', $request->start_roll_numbers[$index]);
            }
            
            $students = $query->with(['grade', 'section'])->orderBy('roll_number')->get();
            
            $studentsByGrade[] = $students;
            if ($students->count() > $maxCount) {
                $maxCount = $students->count();
            }
        }

        // Merge logic: Interleave
        // If we have 3 classes: A, B, C
        // A1, B1, C1, A2, B2, C2, ...
        // If one runs out, continue with others.
        
        $mergedStudents = [];
        for ($i = 0; $i < $maxCount; $i++) {
            foreach ($studentsByGrade as $gradeStudents) {
                if (isset($gradeStudents[$i])) {
                    $mergedStudents[] = $gradeStudents[$i];
                }
            }
        }

        return view('exams.sheet_plan_print', compact('school', 'roomNo', 'selectedGrades', 'mergedStudents'));
    }
}
