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
        Schema::create('bus_routings', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('term');
            $table->string('session');
            $table->string('admission_number');
            $table->string('student_id');
            $table->string('bus_type');
            $table->string('bus_number');
            $table->string('driver_name');
            $table->string('driver_phonenumber');
            $table->string('driver_image');
            $table->string('conductor_name');
            $table->string('conductor_phonenumber');
            $table->string('conductor_image');
            $table->string('route');
            $table->string('ways');
            $table->string('pickup_time');
            $table->string('dropoff_time');
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
        Schema::dropIfExists('bus_routings');
    }
};
