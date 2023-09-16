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
        Schema::create('health_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('admission_number');
            $table->string('student_id');
            $table->string('student_fullname');
            $table->string('date_of_incident');
            $table->string('time_of_incident');
            $table->string('condition');
            $table->string('state');
            $table->string('report_details');
            $table->string('action_taken');
            $table->string('recommendation');
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
        Schema::dropIfExists('health_reports');
    }
};
