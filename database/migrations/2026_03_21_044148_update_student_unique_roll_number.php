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
        Schema::table('students', function (Blueprint $table) {
            // Drop the single unique index on roll_number
            $table->dropUnique(['roll_number']);
            // Add a composite unique index for school_id and roll_number
            $table->unique(['school_id', 'roll_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'roll_number']);
            $table->unique(['roll_number']);
        });
    }
};
