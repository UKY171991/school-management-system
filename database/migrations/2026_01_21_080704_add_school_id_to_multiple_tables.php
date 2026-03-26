<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['students', 'teachers', 'grades', 'sections', 'subjects', 'exams', 'marks', 'fee_structures', 'books', 'hostels'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $tableGroup) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'school_id')) {
                        $tableGroup->unsignedBigInteger('school_id')->nullable()->after('id');
                        $tableGroup->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['students', 'teachers', 'grades', 'sections', 'subjects', 'exams', 'marks', 'fee_structures', 'books', 'hostels'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableGroup) {
                $tableGroup->dropForeign(['school_id']);
                $tableGroup->dropColumn('school_id');
            });
        }
    }
};
