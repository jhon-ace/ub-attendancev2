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
        Schema::create('working_hour', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('school_id');
             $table->unsignedBigInteger('department_id');
             $table->unsignedTinyInteger('day_of_week')->nullable(); //Added column for day of the week (1-7)
             $table->time('morning_start_time')->nullable();
            $table->time('morning_end_time')->nullable();
            $table->time('afternoon_start_time')->nullable();
            $table->time('afternoon_end_time')->nullable();
            $table->timestamps();

             $table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_hour');
    }
};
