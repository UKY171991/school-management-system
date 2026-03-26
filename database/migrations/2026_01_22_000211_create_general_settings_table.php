<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('general_settings')) {
            Schema::create('general_settings', function (Blueprint $table) {
                $table->id();
                $table->string('school_name')->default('School Name');
                $table->string('school_address')->nullable();
                $table->string('school_phone')->nullable();
                $table->string('school_email')->nullable();
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('footer_text')->nullable();
                $table->string('currency_symbol')->default('$');
                $table->timestamps();
            });

            // Insert default settings
            DB::table('general_settings')->insert([
                'school_name' => 'My School',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
