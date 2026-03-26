<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Exam;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\TransportRoute;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Homework;
use App\Models\Syllabus;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\ExamTimetable;
use App\Models\Salary;

class SchoolApiController extends Controller
{
    /**
     * Get school information by domain
     */
    public function getSchoolInfo(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $school->id,
                'name' => $school->name,
                'address' => $school->address,
                'phone' => $school->phone,
                'email' => $school->email,
                'domain_name' => $school->domain_name,
                'logo_url' => $school->logo_url,
            ]
        ]);
    }

    /**
     * Get all students for the school
     */
    public function getStudents(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $students = Student::where('school_id', $school->id)
            ->with(['grade', 'section', 'branch'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Get single student by ID or roll number
     */
    public function getStudent(Request $request, $identifier)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $student = Student::where('school_id', $school->id)
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('roll_number', $identifier);
            })
            ->with(['grade', 'section', 'branch'])
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Get all teachers for the school
     */
    public function getTeachers(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $teachers = Teacher::where('school_id', $school->id)
            ->with('branch')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * Get all classes/grades for the school
     */
    public function getGrades(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $grades = Grade::where('school_id', $school->id)
            ->with(['sections', 'teacher', 'branch'])
            ->withCount('students')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $grades
        ]);
    }

    /**
     * Get all sections for the school
     */
    public function getSections(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $sections = Section::where('school_id', $school->id)
            ->with(['grade', 'branch'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    /**
     * Get all exams for the school
     */
    public function getExams(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $exams = Exam::where('school_id', $school->id)
            ->orderBy('date', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $exams
        ]);
    }

    /**
     * Get attendance records
     */
    public function getAttendance(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $query = Attendance::where('school_id', $school->id)
            ->with('student');

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $attendance = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Get fee payments
     */
    public function getFeePayments(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $query = FeePayment::where('school_id', $school->id)
            ->with('student');

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Get school statistics
     */
    public function getStatistics(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $stats = [
            'total_students' => Student::where('school_id', $school->id)->count(),
            'total_teachers' => Teacher::where('school_id', $school->id)->count(),
            'total_grades' => Grade::where('school_id', $school->id)->count(),
            'total_sections' => Section::where('school_id', $school->id)->count(),
            'total_exams' => Exam::where('school_id', $school->id)->count(),
            'today_attendance' => Attendance::where('school_id', $school->id)
                ->whereDate('date', today())
                ->count(),
            'present_today' => Attendance::where('school_id', $school->id)
                ->whereDate('date', today())
                ->where('status', 'present')
                ->count(),
            'total_fees_collected' => FeePayment::where('school_id', $school->id)
                ->sum('amount_paid'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get branches for the school
     */
    public function getBranches(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        
        if (!$school) {
            return response()->json(['error' => 'School not found'], 404);
        }

        $branches = $school->branches()
            ->withCount(['students', 'teachers'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $branches
        ]);
    }

    /**
     * Get library books
     */
    public function getBooks(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $books = Book::where('school_id', $school->id)
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $books]);
    }

    /**
     * Get homework list
     */
    public function getHomework(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $homework = Homework::where('school_id', $school->id)
            ->with(['grade', 'section', 'subject'])
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $homework]);
    }

    /**
     * Get syllabus list
     */
    public function getSyllabus(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $syllabus = Syllabus::where('school_id', $school->id)
            ->with(['grade', 'subject'])
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $syllabus]);
    }

    /**
     * Get transport routes
     */
    public function getTransportRoutes(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $routes = TransportRoute::where('school_id', $school->id)
            ->get();

        return response()->json(['success' => true, 'data' => $routes]);
    }

    /**
     * Get hostels
     */
    public function getHostels(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $hostels = Hostel::where('school_id', $school->id)
            ->with('rooms')
            ->get();

        return response()->json(['success' => true, 'data' => $hostels]);
    }

    /**
     * Get subject list
     */
    public function getSubjects(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $subjects = Subject::where('school_id', $school->id)
            ->get();

        return response()->json(['success' => true, 'data' => $subjects]);
    }

    /**
     * Get exam marks/results
     */
    public function getExamResults(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $query = Mark::where('school_id', $school->id)
            ->with(['student', 'exam', 'subject']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $results = $query->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $results]);
    }

    /**
     * Get exam timetables
     */
    public function getExamTimetables(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $query = ExamTimetable::where('school_id', $school->id)
            ->with(['exam', 'grade', 'subject']);

        if ($request->has('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $timetables = $query->get();

        return response()->json(['success' => true, 'data' => $timetables]);
    }

    /**
     * Get staff payroll/salary info
     */
    public function getPayroll(Request $request)
    {
        $school = $this->getSchoolByDomain($request);
        if (!$school) return response()->json(['error' => 'School not found'], 404);

        $query = Salary::where('school_id', $school->id)
            ->with('staff');

        if ($request->has('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $payroll = $query->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $payroll]);
    }

    /**
     * Helper method to get school by domain from request
     */
    private function getSchoolByDomain(Request $request)
    {
        // Try to get domain from header first
        $domain = $request->header('X-School-Domain');
        
        // If not in header, try from query parameter
        if (!$domain) {
            $domain = $request->get('domain');
        }
        
        // If still not found, try from host
        if (!$domain) {
            $domain = $request->getHost();
        }

        return School::where('domain_name', $domain)->first();
    }
}
