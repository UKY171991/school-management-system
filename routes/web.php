<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\FrontendController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\FrontendController::class, 'about'])->name('about');
Route::get('/courses', [App\Http\Controllers\FrontendController::class, 'courses'])->name('courses');
Route::get('/contact', [App\Http\Controllers\FrontendController::class, 'contact'])->name('contact');

Auth::routes();

Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard');

    // API Documentation
    Route::get('/api-documentation', [App\Http\Controllers\ApiDocumentationController::class, 'index'])->name('admin.api-docs');

    Route::delete('schools/{id}/delete-asset', [App\Http\Controllers\SchoolController::class, 'deleteAsset'])->name('schools.delete-asset');
    Route::resource('schools', App\Http\Controllers\SchoolController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);

    // 1. Student Management
    Route::delete('admissions/{id}/delete-photo', [App\Http\Controllers\AdmissionController::class, 'deletePhoto'])->name('admissions.delete-photo');
    Route::get('admissions/bulk', [App\Http\Controllers\AdmissionController::class, 'bulkAdmission'])->name('admissions.bulk');
    Route::post('admissions/import', [App\Http\Controllers\AdmissionController::class, 'import'])->name('admissions.import');
    Route::get('admissions/download-sample', [App\Http\Controllers\AdmissionController::class, 'downloadSample'])->name('admissions.download-sample');
    Route::get('admissions/print/blank', [App\Http\Controllers\AdmissionController::class, 'printBlank'])->name('admissions.print-blank');
    Route::get('admissions/print/{id}', [App\Http\Controllers\AdmissionController::class, 'print'])->name('admissions.print');
    Route::resource('admissions', App\Http\Controllers\AdmissionController::class);
    Route::get('students', [App\Http\Controllers\AdmissionController::class, 'index'])->name('students.index');
    Route::resource('student-profiles', App\Http\Controllers\StudentProfileController::class);
    Route::get('attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
    Route::resource('attendance', App\Http\Controllers\AttendanceController::class);
    Route::resource('transfers', App\Http\Controllers\TransferController::class);

    // Student List Print
    Route::get('students/print-menu', [App\Http\Controllers\StudentListPrintController::class, 'index'])->name('students.print_menu');
    Route::get('students/print-list', [App\Http\Controllers\StudentListPrintController::class, 'print'])->name('students.print');

    // 2. Teacher & Staff
    Route::delete('teacher-profiles/{id}/delete-asset', [App\Http\Controllers\TeacherController::class, 'deleteAsset'])->name('teacher-profiles.delete-asset');
    Route::resource('teacher-profiles', App\Http\Controllers\TeacherController::class);
    Route::resource('staff-attendance', App\Http\Controllers\StaffAttendanceController::class);
    Route::get('teacher-timetable/routine', [App\Http\Controllers\TeacherTimetableController::class, 'routine'])->name('teacher-timetable.routine');
    Route::post('update-grades-order', [App\Http\Controllers\TeacherTimetableController::class, 'updateGradesOrder']);
    Route::resource('teacher-timetable', App\Http\Controllers\TeacherTimetableController::class);
    Route::resource('timetable-slots', App\Http\Controllers\TimetableSlotController::class);
    Route::resource('payroll', App\Http\Controllers\PayrollController::class);

    // 3. Academic
    Route::resource('branches', App\Http\Controllers\BranchController::class);
    Route::resource('grades', App\Http\Controllers\GradeController::class);
    Route::resource('sections', App\Http\Controllers\SectionController::class);
    Route::resource('subjects', App\Http\Controllers\SubjectController::class);

    Route::resource('syllabus', App\Http\Controllers\SyllabusController::class);
    Route::resource('homework', App\Http\Controllers\HomeworkController::class);

    // 5. Exam
    Route::post('exams/generate-timetable', [App\Http\Controllers\ExamController::class, 'generateTimetable'])->name('exams.generate');
    Route::resource('exam-types', App\Http\Controllers\ExamTypeController::class);
    Route::resource('exams', App\Http\Controllers\ExamController::class);
    Route::resource('exam-timetable', App\Http\Controllers\ExamTimetableController::class);
    Route::get('exam-results/get-exams-by-school', [App\Http\Controllers\ExamResultController::class, 'getExamsBySchool'])->name('exam-results.get-exams-by-school');
    Route::get('exam-results/get-grades-by-school', [App\Http\Controllers\ExamResultController::class, 'getGradesBySchool'])->name('exam-results.get-grades-by-school');
    Route::get('exam-results/student/{id}', [App\Http\Controllers\ExamResultController::class, 'getStudentResults'])->name('exam-results.student-results');
    Route::get('exam-results/print/{id}', [App\Http\Controllers\ExamResultController::class, 'printResult'])->name('exam-results.print');
    Route::get('exam-results/print-full/{id}', [App\Http\Controllers\ExamResultController::class, 'printFullResult'])->name('exam-results.print-full');
    Route::get('exam-results/statistics', [App\Http\Controllers\ExamResultController::class, 'statistics'])->name('exam-results.statistics');
    Route::resource('exam-results', App\Http\Controllers\ExamResultController::class);
    
    // Exam Sheet Plan
    Route::get('exam-sheet-plan', [App\Http\Controllers\ExamSheetPlanController::class, 'index'])->name('exam-sheet-plan.index');
    Route::post('exam-sheet-plan/generate', [App\Http\Controllers\ExamSheetPlanController::class, 'generate'])->name('exam-sheet-plan.generate');

    // Admit Card
    Route::get('admit-cards', [App\Http\Controllers\AdmitCardController::class, 'index'])->name('admit-cards.index');
    Route::get('admit-cards/{id}', [App\Http\Controllers\AdmitCardController::class, 'show'])->name('admit-cards.show');

    Route::resource('marks', App\Http\Controllers\MarkController::class);
    Route::resource('report-cards', App\Http\Controllers\ReportCardController::class);

    // 6. Fee
    Route::resource('fee-structure', App\Http\Controllers\FeeStructureController::class);
    Route::resource('fee-payments', App\Http\Controllers\FeePaymentController::class);

    // 7. Library
    Route::resource('books', App\Http\Controllers\BookController::class);
    Route::resource('book-issue', App\Http\Controllers\BookIssueController::class);

    // 8. Transport
    Route::resource('transport-routes', App\Http\Controllers\TransportRouteController::class);
    Route::resource('drivers', App\Http\Controllers\DriverController::class);
    Route::resource('vehicles', App\Http\Controllers\VehicleController::class);

    // 9. Hostel
    Route::resource('hostels', App\Http\Controllers\HostelController::class);
    Route::resource('hostel-rooms', App\Http\Controllers\HostelRoomController::class);

    // 10. Communication
    Route::resource('communication', App\Http\Controllers\CommunicationController::class);

    // 11. Portals
    Route::get('portal-student', [App\Http\Controllers\PortalController::class, 'student'])->name('portal-student');
    Route::get('portal-teacher', [App\Http\Controllers\PortalController::class, 'teacher'])->name('portal-teacher');
    Route::get('portal-parent', [App\Http\Controllers\PortalController::class, 'parent'])->name('portal-parent');

    // Account Settings
    Route::get('settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings');
    Route::get('settings/profile', [App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings.profile.view');
    Route::get('settings/password', [App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings.password.view');
    
    Route::post('settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('admin.settings.profile');
    Route::post('settings/password', [App\Http\Controllers\SettingsController::class, 'changePassword'])->name('admin.settings.password');

    Route::get('api-docs', function() {
        return view('admin.api_docs');
    })->name('admin.api-docs');

    // System Maintenance (Master Admin Only)
    Route::middleware(['master.admin'])->prefix('maintenance')->group(function () {
        Route::post('/optimize', [App\Http\Controllers\SystemMaintenanceController::class, 'optimize'])->name('admin.maintenance.optimize');
        Route::post('/migrate', [App\Http\Controllers\SystemMaintenanceController::class, 'migrate'])->name('admin.maintenance.migrate');
        Route::post('/storage-link', [App\Http\Controllers\SystemMaintenanceController::class, 'storageLink'])->name('admin.maintenance.storage-link');
        Route::post('/composer-update', [App\Http\Controllers\SystemMaintenanceController::class, 'composerUpdate'])->name('admin.maintenance.composer-update');
    });

    // System Settings
    Route::get('general-settings', [App\Http\Controllers\GeneralSettingsController::class, 'index'])->name('admin.general-settings.index');
    Route::post('general-settings/update', [App\Http\Controllers\GeneralSettingsController::class, 'update'])->name('admin.general-settings.update');
});
