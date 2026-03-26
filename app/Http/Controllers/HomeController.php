<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $isMasterAdmin = $user->isMasterAdmin();
        $schoolId = $user->school_id;
        $branchId = $user->branch_id;

        // Base counts with role-based filtering
        $studentCount = $isMasterAdmin 
            ? \App\Models\Student::count() 
            : \App\Models\Student::where('school_id', $schoolId)->count();

        $teacherCount = $isMasterAdmin 
            ? \App\Models\Teacher::count() 
            : \App\Models\Teacher::where('school_id', $schoolId)->count();

        $subjectCount = $isMasterAdmin 
            ? \App\Models\Subject::count() 
            : \App\Models\Subject::where('school_id', $schoolId)->count();

        $schoolCount = $isMasterAdmin ? \App\Models\School::count() : 1;

        $examCount = $isMasterAdmin 
            ? \App\Models\Exam::count() 
            : \App\Models\Exam::where('school_id', $schoolId)->count();

        $bookCount = $isMasterAdmin 
            ? \App\Models\Book::count() 
            : \App\Models\Book::where('school_id', $schoolId)->count();

        $sectionCount = $isMasterAdmin 
            ? \App\Models\Section::count() 
            : \App\Models\Section::where('school_id', $schoolId)->count();

        $hostelCount = $isMasterAdmin 
            ? \App\Models\Hostel::count() 
            : \App\Models\Hostel::where('school_id', $schoolId)->count();

        $gradeCount = $isMasterAdmin 
            ? \App\Models\Grade::count() 
            : \App\Models\Grade::where('school_id', $schoolId)->count();

        $branchCount = $isMasterAdmin 
            ? \App\Models\Branch::count() 
            : \App\Models\Branch::where('school_id', $schoolId)->count();

        // Latest students
        $latestStudents = $isMasterAdmin 
            ? \App\Models\Student::with(['grade', 'section', 'branch'])->latest()->take(5)->get()
            : \App\Models\Student::with(['grade', 'section', 'branch'])->where('school_id', $schoolId)->latest()->take(5)->get();

        // Recent activities
        $recentTeachers = $isMasterAdmin 
            ? \App\Models\Teacher::latest()->take(5)->get()
            : \App\Models\Teacher::where('school_id', $schoolId)->latest()->take(5)->get();

        // Attendance stats (today)
        $todayAttendance = 0;
        $presentToday = 0;
        
        try {
            $todayAttendance = $isMasterAdmin 
                ? \App\Models\Attendance::whereDate('date', today())->count()
                : \App\Models\Attendance::where('school_id', $schoolId)->whereDate('date', today())->count();

            $presentToday = $isMasterAdmin 
                ? \App\Models\Attendance::whereDate('date', today())->where('status', 'present')->count()
                : \App\Models\Attendance::where('school_id', $schoolId)->whereDate('date', today())->where('status', 'present')->count();
        } catch (\Exception $e) {
            \Log::error('Attendance query error: ' . $e->getMessage());
        }

        // Fee collection stats
        $totalFees = 0;
        $pendingFees = 0;
        
        try {
            $totalFees = $isMasterAdmin 
                ? \App\Models\FeePayment::sum('amount_paid')
                : \App\Models\FeePayment::where('school_id', $schoolId)->sum('amount_paid');

            $pendingFees = $isMasterAdmin 
                ? \App\Models\FeePayment::where('status', 'pending')->count()
                : \App\Models\FeePayment::where('school_id', $schoolId)->where('status', 'pending')->count();
        } catch (\Exception $e) {
            \Log::error('Fee payment query error: ' . $e->getMessage());
        }

        // Upcoming exams
        $upcomingExams = collect();
        try {
            $upcomingExams = $isMasterAdmin 
                ? \App\Models\Exam::where('date', '>=', today())->orderBy('date')->take(5)->get()
                : \App\Models\Exam::where('school_id', $schoolId)->where('date', '>=', today())->orderBy('date')->take(5)->get();
        } catch (\Exception $e) {
            \Log::error('Upcoming exams query error: ' . $e->getMessage());
        }

        // Branch-wise student distribution (for school admins)
        $branchStats = [];
        if (!$isMasterAdmin && $schoolId) {
            try {
                $branches = \App\Models\Branch::where('school_id', $schoolId)->get();
                foreach ($branches as $branch) {
                    $branchStats[] = [
                        'name' => $branch->name,
                        'students' => \App\Models\Student::where('branch_id', $branch->id)->count(),
                        'teachers' => \App\Models\Teacher::where('branch_id', $branch->id)->count(),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Branch stats query error: ' . $e->getMessage());
            }
        }

        // Grade-wise distribution
        $gradeStats = collect();
        try {
            $gradeStats = $isMasterAdmin 
                ? \App\Models\Grade::withCount('students')->take(10)->get()
                : \App\Models\Grade::where('school_id', $schoolId)->withCount('students')->take(10)->get();
        } catch (\Exception $e) {
            \Log::error('Grade stats query error: ' . $e->getMessage());
        }

        return view('dashboard', compact(
            'studentCount', 
            'teacherCount', 
            'subjectCount', 
            'schoolCount', 
            'examCount', 
            'bookCount', 
            'sectionCount', 
            'hostelCount',
            'gradeCount',
            'branchCount',
            'latestStudents',
            'recentTeachers',
            'todayAttendance',
            'presentToday',
            'totalFees',
            'pendingFees',
            'upcomingExams',
            'branchStats',
            'gradeStats',
            'isMasterAdmin'
        ));
    }
}
