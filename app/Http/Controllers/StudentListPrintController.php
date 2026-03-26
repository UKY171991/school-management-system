<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\GeneralSetting;

use App\Models\Subject;
use App\Models\ExamType;
use App\Models\School;

class StudentListPrintController extends Controller
{
    public function index()
    {
        $schools = School::all();
        $grades = Grade::with('teacher')->get();
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $examTypes = ExamType::all();
        return view('students.print_menu', compact('schools', 'grades', 'teachers', 'subjects', 'examTypes'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable|exists:sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type_id' => 'nullable|exists:exam_types,id',
        ]);

        $school = School::findOrFail($request->school_id);
        $grade = Grade::findOrFail($request->grade_id);
        $teacher = Teacher::findOrFail($request->teacher_id);
        $subject = Subject::findOrFail($request->subject_id);
        
        $examType = null;
        if ($request->has('exam_type_id') && !empty($request->exam_type_id)) {
            $examType = ExamType::findOrFail($request->exam_type_id);
        }

        $query = Student::where('grade_id', $grade->id);

        if ($request->has('section_id') && !empty($request->section_id)) {
            $query->where('section_id', $request->section_id);
        }

        $section = null;
        if ($request->has('section_id') && !empty($request->section_id)) {
            $section = \App\Models\Section::find($request->section_id);
        }

        $students = $query->orderBy('roll_number')->get();
        
        // Use School settings but fallback to GeneralSetting for structure if needed, 
        // though print_list view seems to handle $settings object which was GeneralSetting.
        // We will pass $school as $settings because School model has similar fields (name, address, email, phone, logo).
        // Let's verify mapping:
        // GeneralSetting: school_name, school_address, school_phone, school_email, logo
        // School: name, address, phone, email, logo
        // We can create a temporary object or just pass school and update view to check for name vs school_name.
        // Or simpler: adapt the view to use properties that exist on both or prefer one.
        // Let's pass $school as separate variable and update view.
        
        return view('students.print_list', compact('school', 'grade', 'teacher', 'subject', 'students', 'examType', 'section'));
    }
}
