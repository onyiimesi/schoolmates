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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("designation_id");
            $table->string('department');
            $table->string('surname');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('username');
            $table->foreign('designation_id')
            ->references('id')
            ->on('designations');
            $table->string('email')->unique();
            $table->string('phoneno');
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->string('pass_word')->nullable();
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
        Schema::dropIfExists('staff');
    }
};
