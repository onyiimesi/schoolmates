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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('admission_number');
            $table->string('student_fullname');
            $table->string('class');
            $table->string('sub_class');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->string('status')->default('Absent');
            $table->string('attendance_date');
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
        Schema::dropIfExists('student_attendances');
    }
};
