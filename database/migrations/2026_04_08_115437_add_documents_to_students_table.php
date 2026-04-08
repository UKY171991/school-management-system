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
            $table->string('doc_aadhar_birth')->nullable()->after('photo');
            $table->string('doc_father_aadhar')->nullable()->after('doc_aadhar_birth');
            $table->string('doc_mother_aadhar')->nullable()->after('doc_father_aadhar');
            $table->string('doc_marksheet')->nullable()->after('doc_mother_aadhar');
            $table->string('doc_tc')->nullable()->after('doc_marksheet');
            $table->string('doc_admission_form')->nullable()->after('doc_tc');
            $table->string('doc_declaration_form')->nullable()->after('doc_admission_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'doc_aadhar_birth',
                'doc_father_aadhar',
                'doc_mother_aadhar',
                'doc_marksheet',
                'doc_tc',
                'doc_admission_form',
                'doc_declaration_form'
            ]);
        });
    }
};
