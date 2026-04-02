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
        Schema::create('stationary_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stationary_id')->constrained()->cascadeOnDelete();
            $table->string('sch_id');
            $table->string('campus');
            $table->integer('class_id');
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamps();

            $table->index(['stationary_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stationary_sales');
    }
};
