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
        if (!Schema::hasTable('cbt_results')) {
            Schema::create('cbt_results', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->string('period');
                $table->string('term');
                $table->string('session');
                $table->unsignedBigInteger('cbt_answer_id');
                $table->string('student_id');
                $table->string('subject_id');
                $table->string('question_type');
                $table->longText('answer_score');
                $table->string('correct_answer');
                $table->string('incorrect_answer');
                $table->string('total_answer');
                $table->string('student_total_mark');
                $table->string('test_total_mark');
                $table->string('student_duration');
                $table->string('test_duration');

                $table->foreign('cbt_answer_id')->references('id')->on('cbt_answers')->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cbt_performances')) {
            Schema::create('cbt_performances', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->string('period');
                $table->string('term');
                $table->string('session');
                $table->unsignedBigInteger('cbt_result_id');
                $table->string('student_id');
                $table->string('subject_id');
                $table->string('question_type');
                $table->string('correct_answer');
                $table->string('incorrect_answer');
                $table->string('total_answer');
                $table->string('student_total_mark');
                $table->string('test_total_mark');
                $table->string('student_duration');
                $table->string('test_duration');

                $table->foreign('cbt_result_id')->references('id')->on('cbt_results')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_results');
    }
};
