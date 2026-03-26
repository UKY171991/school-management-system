<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'students',
            'teachers',
            'grades',
            'sections',
            'subjects',
            'attendances',
            'exams',
            'marks',
            'fee_structures',
            'fee_payments',
            'books',
            'book_issues',
            'transport_routes',
            'vehicles',
            'drivers',
            'hostels',
            'hostel_rooms',
            'hostel_allocations',
            'homework',
            'syllabi',
            'teacher_timetables',
            'exam_timetables',
            'salaries',
            'staff_leaves',
        ];

        foreach ($tables as $tableName) {

            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'branch_id')) {

                Schema::table($tableName, function (Blueprint $table) {

                    $table->unsignedBigInteger('branch_id')->nullable();

                });

            }
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'students',
            'teachers',
            'grades',
            'sections',
            'subjects',
            'attendances',
            'exams',
            'marks',
            'fee_structures',
            'fee_payments',
            'books',
            'book_issues',
            'transport_routes',
            'vehicles',
            'drivers',
            'hostels',
            'hostel_rooms',
            'hostel_allocations',
            'homework',
            'syllabi',
            'teacher_timetables',
            'exam_timetables',
            'salaries',
            'staff_leaves',
        ];

        foreach ($tables as $tableName) {

            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {

                Schema::table($tableName, function (Blueprint $table) {

                    $table->dropColumn('branch_id');

                });

            }
        }
    }
};