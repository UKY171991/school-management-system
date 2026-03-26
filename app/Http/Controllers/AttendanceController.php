<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $grade_id = $request->grade_id;
            $school_id = $request->school_id;
            $date = $request->date ?? date('Y-m-d');
            
            $query = \App\Models\Student::where('grade_id', $grade_id)
                ->with(['school', 'attendances' => function($q) use ($date) {
                    $q->where('date', $date);
                }]);
            
            if ($request->has('section_id') && !empty($request->section_id)) {
                $query->where('section_id', $request->section_id);
            }

            $students = $query->get();
                
            return response()->json($students);
        }
        if (auth()->user()->isMasterAdmin()) {
            $schools = \App\Models\School::all();
        } else {
            $schools = \App\Models\School::where('id', auth()->user()->school_id)->get();
        }
        $grades = \App\Models\Grade::all();
        $sections = \App\Models\Section::with('grade')->get();
        return view('attendance.index', compact('schools', 'grades', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'school_id' => 'required|exists:schools,id', // Validated but might vary per student? 
            // Better to fetch student's school_id or assume one school for the batch if filtered.
            // Let's rely on the passed school_id which implies the user is taking attendance for a specific school context.
        ]);

        foreach ($request->attendance as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                [
                    'status' => $status,
                    'school_id' => $request->school_id
                ]
            );
        }

        return response()->json(['success' => __('Attendance updated successfully.')]);

    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $grade_id = $request->grade_id;
            $school_id = $request->school_id;
            $section_id = $request->section_id;
            $month = $request->month;
            $year = $request->year;

            $startDate = \Carbon\Carbon::createFromDate($year, $month, 1);
            $daysInMonth = $startDate->daysInMonth;
            $endDate = $startDate->copy()->endOfMonth();

            $query = \App\Models\Student::where('grade_id', $grade_id)
                ->with(['attendances' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                }]);

            if ($school_id) {
                $query->where('school_id', $school_id);
            }
            if ($section_id) {
                $query->where('section_id', $section_id);
            }

            $students = $query->get();

            $reportData = [];
            foreach ($students as $student) {
                $attendanceMap = [];
                $present = 0;
                $absent = 0;
                $late = 0;
                $excused = 0;

                foreach ($student->attendances as $attendance) {
                    // map day number to status
                    $day = (int)\Carbon\Carbon::parse($attendance->date)->format('d');
                    $attendanceMap[$day] = $attendance->status;
                    
                    if ($attendance->status == 'present') $present++;
                    if ($attendance->status == 'absent') $absent++;
                    if ($attendance->status == 'late') $late++;
                    if ($attendance->status == 'excused') $excused++;
                }

                $reportData[] = [
                    'student' => $student,
                    'attendance' => $attendanceMap,
                    'summary' => [
                        'present' => $present,
                        'absent' => $absent,
                        'late' => $late,
                        'excused' => $excused
                    ]
                ];
            }

            return response()->json([
                'daysInMonth' => $daysInMonth,
                'data' => $reportData
            ]);
        }

        if (auth()->user()->isMasterAdmin()) {
            $schools = \App\Models\School::all();
        } else {
            $schools = \App\Models\School::where('id', auth()->user()->school_id)->get();
        }
        $grades = \App\Models\Grade::all();
        $sections = \App\Models\Section::with('grade')->get();
        return view('attendance.report', compact('schools', 'grades', 'sections'));
    }
}
