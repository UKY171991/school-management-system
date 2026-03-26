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
        $tables = ['transport_routes', 'vehicles', 'homework', 'syllabi', 'attendances', 'class_allocations']; // Note: class_allocations might not exist, checked: ClassAllocation writes to Section/Student. So no class_allocations table.

        // Correct list:
        $tables = ['transport_routes', 'vehicles', 'homework', 'syllabi', 'attendances'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableGroup) {
                    if (!Schema::hasColumn($tableGroup->getTable(), 'school_id')) {
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
        $tables = ['transport_routes', 'vehicles', 'homework', 'syllabi', 'attendances'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableGroup) {
                    if (Schema::hasColumn($tableGroup->getTable(), 'school_id')) {
                        $tableGroup->dropForeign(['school_id']);
                        $tableGroup->dropColumn('school_id');
                    }
                });
            }
        }
    }
};
