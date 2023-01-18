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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('genotype');
            $table->string('blood_group');
            $table->string('gender');
            $table->string('dob');
            $table->string('nationality');
            $table->string('state');
            $table->string('session_admitted');
            $table->string('class');
            $table->string('present_class');
            $table->string('image');
            $table->string('home_address');
            $table->string('phone_number');
            $table->string('email_address');
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
        Schema::dropIfExists('students');
    }
};
