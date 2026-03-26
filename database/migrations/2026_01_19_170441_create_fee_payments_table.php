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
        if (!Schema::hasTable('fee_payments')) {
            Schema::create('fee_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('fee_structure_id')->constrained()->onDelete('cascade');
                $table->decimal('amount_paid', 10, 2);
                $table->date('payment_date');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
