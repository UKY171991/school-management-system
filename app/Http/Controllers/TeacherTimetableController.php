<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeacherTimetable;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Subject;

class TeacherTimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TeacherTimetable::with(['teacher.school', 'section.grade', 'subject', 'user']);
            
            if ($request->has('school_id') && !empty($request->school_id)) {
                $schoolId = $request->school_id;
                $query->whereHas('teacher', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                });
            }

            if ($request->has('teacher_id') && !empty($request->teacher_id)) {
                $query->where('teacher_id', $request->teacher_id);
            }

            // Join for filtering by Class and sorting
            $query->join('sections', 'teacher_timetables.section_id', '=', 'sections.id', 'left')
                  ->join('grades', 'sections.grade_id', '=', 'grades.id', 'left')
                  ->select('teacher_timetables.*');

            if ($request->has('grade_id') && !empty($request->grade_id)) {
                $query->where('sections.grade_id', $request->grade_id);
            }

            if ($request->has('section_id') && !empty($request->section_id)) {
                $query->where('teacher_timetables.section_id', $request->section_id);
            }

            // Sort by Grade Name (Class Name)
            $query->orderBy('grades.name', 'asc');

            $timetables = $query->get();
            return response()->json($timetables);
        }

        if ($request->get('view') == 'list') {
            $schools = \App\Models\School::all();
            $teachers = Teacher::all();
            $grades = \App\Models\Grade::orderBy('name', 'asc')->get();
            $sections = Section::with('grade')->get(); 
            $subjects = Subject::all();

            return view('teacher-timetable.index', compact('schools', 'teachers', 'grades', 'sections', 'subjects'));
        }

        // Default to Routine View as requested
        return $this->routine($request);
    }

    public function routine(Request $request)
    {
        $user = auth()->user();
        $isMasterAdmin = $user->isMasterAdmin();
        $selectedDay = $request->get('day', 'Monday');
        
        // Default school_id for non-master admins, otherwise MUST be selected from request
        $schoolId = $isMasterAdmin ? $request->get('school_id') : $user->school_id;

        $grades = collect();
        $teachers = collect();
        $subjects = collect();
        $timetables = collect();

        if ($schoolId) {
            $grades = \App\Models\Grade::with('sections')
                ->where('school_id', $schoolId)
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            $teachers = Teacher::where('school_id', $schoolId)->get();
            $subjects = Subject::where('school_id', $schoolId)->get();
            $timetables = TeacherTimetable::with(['teacher', 'subject', 'section.grade', 'grade'])
                ->where('day', $selectedDay)
                ->where('school_id', $schoolId)
                ->get();
        }

        $allSections = \App\Models\Section::with('grade')->get();
        
        // Load slots scoped to the selected school
        $slotsQuery = \App\Models\TimetableSlot::orderBy('sort_order', 'asc');
        if ($schoolId) {
            $slotsQuery->where('school_id', $schoolId);
        }
        $slots = $slotsQuery->get();
        
        $schools = $isMasterAdmin ? \App\Models\School::all() : \App\Models\School::where('id', $user->school_id)->get();

        return view('teacher-timetable.routine', compact('grades', 'allSections', 'timetables', 'selectedDay', 'slots', 'schools', 'teachers', 'subjects', 'schoolId', 'isMasterAdmin'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->isMasterAdmin() ? $request->get('school_id') : $user->school_id;

        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable', 
            'subject_id' => 'required',
            'exam_date' => 'nullable|date',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $data = $validated;
        $data['school_id'] = $schoolId;
        $data['user_id'] = $user->id;

        $request->merge(['school_id' => $schoolId]);

        // Specific Conflict Checks
        if ($this->teacherIsBusy($request)) {
             return response()->json([
                'message' => 'This Teacher is already scheduled for another class at this time.',
                'errors' => ['teacher' => ['This Teacher is already scheduled for another class at this time.']]
             ], 422);
        }

        if ($this->sectionIsBusy($request)) {
             return response()->json([
                'message' => 'This Class/Section already has a subject scheduled for this time slot.',
                'errors' => ['section' => ['This Class/Section already has a subject scheduled for this time slot.']]
             ], 422);
        }
        
        if ($this->hasDuplicateSubject($request)) {
             return response()->json([
                'message' => 'This Subject is already scheduled (possible duplicate).',
                'errors' => ['duplicate' => ['This Subject is already scheduled (possible duplicate).']]
             ], 422);
        }

        $timetable = TeacherTimetable::create($data);

        return response()->json(['success' => 'Timetable entry created successfully.', 'timetable' => $timetable]);
    }

    public function show(string $id)
    {
        $timetable = TeacherTimetable::with(['teacher.school', 'section.grade', 'grade', 'subject'])->findOrFail($id);
        
        // Deny access if not master admin and school doesn't match
        if (!auth()->user()->isMasterAdmin() && $timetable->school_id != auth()->user()->school_id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return response()->json($timetable);
    }

    public function update(Request $request, string $id)
    {
        $timetable = TeacherTimetable::findOrFail($id);
        $user = auth()->user();
        
        if (!$user->isMasterAdmin() && $timetable->school_id != $user->school_id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'nullable',
            'subject_id' => 'required',
            'exam_date' => 'nullable|date',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $schoolId = $user->isMasterAdmin() ? ($request->get('school_id') ?? $timetable->school_id) : $user->school_id;
        $request->merge(['school_id' => $schoolId]);

        // Specific Conflict Checks for Update
        if ($this->teacherIsBusy($request, $id)) {
             return response()->json([
                'message' => 'This Teacher is already scheduled for another class at this time.',
                'errors' => ['teacher' => ['This Teacher is already scheduled for another class at this time.']]
             ], 422);
        }

        if ($this->sectionIsBusy($request, $id)) {
             return response()->json([
                'message' => 'This Class/Section already has a subject scheduled for this time slot.',
                'errors' => ['section' => ['This Class/Section already has a subject scheduled for this time slot.']]
             ], 422);
        }
        
        if ($this->hasDuplicateSubject($request, $id)) {
             return response()->json(['message' => 'Possible duplicate entry.'], 422);
        }

        $data = $validated;
        $data['school_id'] = $schoolId;
        $timetable->update($data);

        return response()->json(['success' => 'Timetable entry updated successfully.', 'timetable' => $timetable]);
    }

    public function updateGradesOrder(Request $request)
    {
        $sortOrders = $request->get('sort_orders', []);
        foreach ($sortOrders as $gradeId => $order) {
            \App\Models\Grade::where('id', $gradeId)->update(['sort_order' => $order]);
        }
        return response()->json(['success' => 'Class order updated successfully.']);
    }

    private function teacherIsBusy(Request $request, $ignoreId = null)
    {
        if (!$request->filled('teacher_id')) return false;

        $schoolId = $request->get('school_id');
        $query = TeacherTimetable::where('day', $request->day)
            ->where('school_id', $schoolId)
            ->where('teacher_id', $request->teacher_id)
            ->where(function ($q) use ($request) {
                // Check Time Overlap
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            });

        if ($ignoreId) $query->where('id', '!=', $ignoreId);

        return $query->exists();
    }

    private function sectionIsBusy(Request $request, $ignoreId = null)
    {
        $schoolId = $request->get('school_id');
        $query = TeacherTimetable::where('day', $request->day)
            ->where('school_id', $schoolId)
            ->where(function($q) use ($request) {
                if ($request->filled('section_id')) {
                    $q->where('section_id', $request->section_id);
                } else {
                    $q->where('grade_id', $request->grade_id)->whereNull('section_id');
                }
            })
            ->where(function ($q) use ($request) {
                // Check Time Overlap
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            });

        if ($ignoreId) $query->where('id', '!=', $ignoreId);

        return $query->exists();
    }
    
    private function hasDuplicateSubject(Request $request, $ignoreId = null)
    {
        $schoolId = $request->get('school_id');

        $query = TeacherTimetable::where('day', $request->day)
            ->where('school_id', $schoolId)
            ->where('subject_id', $request->subject_id);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        } else {
            $query->where('grade_id', $request->grade_id)->whereNull('section_id');
        }

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $timetable = TeacherTimetable::findOrFail($id);
        $timetable->delete();

        return response()->json(['success' => 'Timetable entry deleted successfully.']);
    }
}
