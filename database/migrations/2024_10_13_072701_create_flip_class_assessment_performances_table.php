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
        Schema::create('flip_class_assessment_performances', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->unsignedBigInteger('flip_class_assessment_id');
            $table->string('student_id');
            $table->string('subject_id');
            $table->string('question_type');
            $table->string('total_mark');
            $table->string('percentage_score');
            $table->string('week');

            $table->foreign('flip_class_assessment_id', 'fk_class_assessment_id')->references('id')->on('flip_class_assessments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flip_class_assessment_performances');
    }
};
