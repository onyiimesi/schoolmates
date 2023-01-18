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
        Schema::create('register_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('admission_number');
            $table->string('student_fullname');
            $table->string('class');
            $table->string('sub_class');
            $table->string('subject');
            $table->string('period');
            $table->string('term');
            $table->string('session');
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
        Schema::dropIfExists('register_subjects');
    }
};
