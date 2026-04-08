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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->integer('start_roll_number')->default(1001)->after('currency_symbol');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->integer('start_roll_number')->default(1001)->after('domain_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('start_roll_number');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('start_roll_number');
        });
    }
};
