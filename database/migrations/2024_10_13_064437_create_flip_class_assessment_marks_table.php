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
        if (!Schema::hasTable('flip_class_assessment_marks')) {
            Schema::create('flip_class_assessment_marks', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->string('period');
                $table->string('term');
                $table->string('session');
                $table->unsignedBigInteger('flip_class_assessment_id');
                $table->string('student_id');
                $table->string('subject_id');
                $table->string('topic');
                $table->longText('question');
                $table->string('question_type');
                $table->string('question_number');
                $table->longText('answer');
                $table->longText('correct_answer');
                $table->string('mark');
                $table->string('teacher_mark');
                $table->string('submitted');
                $table->string('week');

                $table->foreign('flip_class_assessment_id')->references('id')->on('flip_class_assessments')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flip_class_assessment_marks');
    }
};
