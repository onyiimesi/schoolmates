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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('student_fullname');
            $table->string('admission_number');
            $table->string('class_name');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->string('subject');
            $table->string('assignment');
            $table->string('test');
            $table->string('exam');
            $table->string('total');
            $table->string('total_subject');
            $table->string('total_student')->nullable();
            $table->string('student_average')->nullable();
            $table->string('class_average')->nullable();
            $table->string('percent_score')->nullable();
            $table->string('grade');
            $table->string('remark');
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
        Schema::dropIfExists('results');
    }
};
