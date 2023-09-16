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
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('staff_id');
            $table->string('vehicle_type');
            $table->string('vehicle_make');
            $table->string('vehicle_number');
            $table->string('driver_name');
            $table->string('detected_fault');
            $table->string('mechanic_name');
            $table->string('mechanic_phone');
            $table->string('cost_of_maintenance');
            $table->string('initial_payment');
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
        Schema::dropIfExists('vehicle_maintenances');
    }
};
