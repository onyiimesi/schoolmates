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
        Schema::create('result_extra_curriculars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->string('name');
            $table->string('value');

            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
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
        Schema::dropIfExists('result_extra_curriculars');
    }
};
