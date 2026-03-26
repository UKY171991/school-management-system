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
        if (!Schema::hasTable('teacher_timetables')) {
            Schema::create('teacher_timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            // assuming sections and subjects tables exist, if not we might need to adjust or create them.
            // For now, let's keep it simple with integers if we are unsure, but ideally constrained.
            // Given the previous context, 'sections' and 'subjects' were mentioned in routes.
            $table->unsignedBigInteger('section_id'); 
            $table->unsignedBigInteger('subject_id');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        }); 
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_timetables');
    }
};
