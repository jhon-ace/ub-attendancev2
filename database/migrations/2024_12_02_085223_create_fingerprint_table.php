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
        Schema::create('fingerprint', function (Blueprint $table) {
            $table->id();
            $table->integer('fingerprint_status')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprint');
    }
};











            // $table->id();
            // $table->unsignedBigInteger('user_id');
            // $table->binary('fingerprint_template');
            // $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('employees')->onDelete('cascade');