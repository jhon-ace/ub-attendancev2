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
        
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('department_id');
            $table->string('employee_photo')->nullable();
            $table->string('employee_id')->unique();
            $table->string('employee_lastname');
            $table->string('employee_firstname');
            $table->string('employee_middlename');
            $table->string('employee_rfid')->unique();
            $table->timestamps();

            // Foreign keys
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('restrict');
        });

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
