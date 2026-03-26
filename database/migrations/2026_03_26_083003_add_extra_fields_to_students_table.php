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
            $table->string('gender')->nullable();
            $table->string('caste')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_phone')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('adhaar_number')->nullable();
            $table->string('apaar_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'gender', 'caste', 'father_phone', 'mother_phone', 
                'admission_date', 'previous_school', 'adhaar_number', 'apaar_id'
            ]);
        });
    }
};
