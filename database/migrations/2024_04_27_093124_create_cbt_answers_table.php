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
        Schema::create('cbt_answers', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->unsignedBigInteger('cbt_question_id');
            $table->string('student_id');
            $table->string('subject_id');
            $table->string('question');
            $table->string('question_number');
            $table->string('question_type');
            $table->string('answer');
            $table->string('correct_answer');
            $table->string('mark_status')->default(0);
            $table->string('submitted')->default(0);
            $table->string('submitted_time')->nullable();
            $table->string('duration');

            $table->index(['cbt_question_id', 'subject_id', 'student_id']);

            $table->foreign('cbt_question_id')->references('id')->on('cbt_questions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_answers');
    }
};
