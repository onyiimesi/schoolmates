<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('assignment_marks')) {
            Schema::create('assignment_marks', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->string('period');
                $table->string('term');
                $table->string('session');
                $table->unsignedBigInteger('assignment_id');
                $table->string('student_id');
                $table->string('subject_id');
                $table->longText('question');
                $table->string('question_type');
                $table->string('question_number');
                $table->longText('answer');
                $table->longText('correct_answer');
                $table->string('mark');
                $table->string('teacher_mark');
                $table->string('submitted');

                $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_marks');
    }
};
