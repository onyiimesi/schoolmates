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
        Schema::create('flip_class_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->string('teacher_id');
            $table->unsignedBigInteger('flip_class_id');
            $table->string('question_type');
            $table->string('topic')->nullable();
            $table->longText('question');
            $table->longText('question_number');
            $table->longText('answer');
            $table->unsignedBigInteger('subject_id');
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('option4')->nullable();
            $table->string('image')->nullable();
            $table->string('total_question')->nullable();
            $table->string('question_mark')->nullable();
            $table->string('total_mark')->nullable();
            $table->string('week')->nullable();
            $table->string('status')->nullable();

            $table->foreign('flip_class_id')->references('id')->on('flip_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flip_class_assessments');
    }
};
