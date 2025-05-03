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
        if (!Schema::hasTable('pre_school_result_extra_curriculars')) {
            Schema::create('pre_school_result_extra_curriculars', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pre_school_result_id');
                $table->string('name');
                $table->string('value');

                $table->foreign('pre_school_result_id')->references('id')->on('pre_school_results')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_school_result_extra_curriculars');
    }
};
