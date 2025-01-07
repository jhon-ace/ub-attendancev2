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
        Schema::table('employees_time_in_attendance', function (Blueprint $table) {
            $table->enum('modification_status', ['modified', 'unmodified'])->default('unmodified')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_time_in_attendance', function (Blueprint $table) {
            $table->dropColumn('modification_status');
        });
    }
};
