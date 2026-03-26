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
        // For SQLite, dropping the table and recreating is more reliable for major changes
        Schema::dropIfExists('marks');
        
        if (!Schema::hasTable('marks')) {
            Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_type_id')->constrained('exam_types')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->float('marks_obtained')->nullable(); // Use float for decimal marks, nullable for absent
            $table->float('max_marks')->default(100);
            $table->text('remarks')->nullable();
            $table->timestamps();
        }); 
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
        if (!Schema::hasTable('marks')) {
            Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('marks_obtained');
            $table->text('remarks')->nullable();
            $table->timestamps();
        }); 
        }
    }
};
