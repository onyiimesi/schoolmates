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
        Schema::create('maximun_scores', function (Blueprint $table) {
            $table->id();
            $table->string('midterm')->nullable();
            $table->string('first_assesment')->nullable();
            $table->string('second_assesment')->nullable();
            $table->string('has_two_assesment')->nullable();
            $table->string('exam');
            $table->string('total');
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
        Schema::dropIfExists('maximun_scores');
    }
};
