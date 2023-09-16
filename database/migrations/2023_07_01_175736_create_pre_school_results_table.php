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
        Schema::create('pre_school_results', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('student_fullname');
            $table->string('admission_number');
            $table->string('class_name');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->json('evaluation_report');
            $table->json('cognitive_development');
            $table->string('school_opened');
            $table->string('times_present');
            $table->string('times_absent');
            $table->string('teacher_comment');
            $table->string('teacher_id');
            $table->string('hos_comment');
            $table->string('hos_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_school_results');
    }
};
