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
        if (!Schema::hasTable('student_transfers')) {
            Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('transfer_date');
            $table->text('reason')->nullable();
            $table->string('to_school')->nullable();
            $table->string('lc_number')->nullable();
            $table->timestamps();
        }); 
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transfers');
    }
};
