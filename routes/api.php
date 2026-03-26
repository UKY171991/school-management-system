<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolApiController;

// Existing routes
Route::prefix('schools')->group(function () {
    Route::get('/by-domain', [\App\Http\Controllers\Api\SchoolDataController::class, 'getByDomain']);
    Route::get('/list', [\App\Http\Controllers\Api\SchoolDataController::class, 'listWithDomains']);
});

// School Domain-based API Routes
Route::prefix('school-api')->middleware('api.domain')->group(function () {
    
    // School Information
    Route::get('/info', [SchoolApiController::class, 'getSchoolInfo']);
    Route::get('/statistics', [SchoolApiController::class, 'getStatistics']);
    Route::get('/branches', [SchoolApiController::class, 'getBranches']);
    
    // Students
    Route::get('/students', [SchoolApiController::class, 'getStudents']);
    Route::get('/students/{identifier}', [SchoolApiController::class, 'getStudent']);
    
    // Teachers
    Route::get('/teachers', [SchoolApiController::class, 'getTeachers']);
    
    // Academic
    Route::get('/grades', [SchoolApiController::class, 'getGrades']);
    Route::get('/sections', [SchoolApiController::class, 'getSections']);
    Route::get('/exams', [SchoolApiController::class, 'getExams']);
    
    // Attendance
    Route::get('/attendance', [SchoolApiController::class, 'getAttendance']);
    
    // Fees
    Route::get('/fee-payments', [SchoolApiController::class, 'getFeePayments']);

    // Library
    Route::get('/books', [SchoolApiController::class, 'getBooks']);

    // Academics
    Route::get('/subjects', [SchoolApiController::class, 'getSubjects']);
    Route::get('/homework', [SchoolApiController::class, 'getHomework']);
    Route::get('/syllabus', [SchoolApiController::class, 'getSyllabus']);

    // Transport & Hostel
    Route::get('/transport/routes', [SchoolApiController::class, 'getTransportRoutes']);
    Route::get('/hostels', [SchoolApiController::class, 'getHostels']);

    // Exam Results & Timetable
    Route::get('/exam-results', [SchoolApiController::class, 'getExamResults']);
    Route::get('/exam-timetables', [SchoolApiController::class, 'getExamTimetables']);

    // Staff & Payroll
    Route::get('/payroll', [SchoolApiController::class, 'getPayroll']);
});

// Public API Routes (without middleware) - for testing
Route::prefix('public-api')->group(function () {
    
    // School Information
    Route::get('/info', [SchoolApiController::class, 'getSchoolInfo']);
    Route::get('/statistics', [SchoolApiController::class, 'getStatistics']);
    Route::get('/branches', [SchoolApiController::class, 'getBranches']);
    
    // Students
    Route::get('/students', [SchoolApiController::class, 'getStudents']);
    Route::get('/students/{identifier}', [SchoolApiController::class, 'getStudent']);
    
    // Teachers
    Route::get('/teachers', [SchoolApiController::class, 'getTeachers']);
    
    // Academic
    Route::get('/grades', [SchoolApiController::class, 'getGrades']);
    Route::get('/sections', [SchoolApiController::class, 'getSections']);
    Route::get('/exams', [SchoolApiController::class, 'getExams']);
    
    // Attendance
    Route::get('/attendance', [SchoolApiController::class, 'getAttendance']);
    
    // Fees
    Route::get('/fee-payments', [SchoolApiController::class, 'getFeePayments']);

    // Library
    Route::get('/books', [SchoolApiController::class, 'getBooks']);

    // Academics
    Route::get('/subjects', [SchoolApiController::class, 'getSubjects']);
    Route::get('/homework', [SchoolApiController::class, 'getHomework']);
    Route::get('/syllabus', [SchoolApiController::class, 'getSyllabus']);

    // Transport & Hostel
    Route::get('/transport/routes', [SchoolApiController::class, 'getTransportRoutes']);
    Route::get('/hostels', [SchoolApiController::class, 'getHostels']);

    // Exam Results & Timetable
    Route::get('/exam-results', [SchoolApiController::class, 'getExamResults']);
    Route::get('/exam-timetables', [SchoolApiController::class, 'getExamTimetables']);

    // Staff & Payroll
    Route::get('/payroll', [SchoolApiController::class, 'getPayroll']);
});
