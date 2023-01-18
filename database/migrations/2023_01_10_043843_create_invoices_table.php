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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id')->nullable();
            $table->string('campus')->nullable();
            $table->string('admission_number');
            $table->string('fullname');
            $table->string('class');
            $table->string('feetype');
            $table->string('amount');
            $table->string('notation')->nullable();
            $table->string('discount');
            $table->string('discount_amount');
            $table->string('term');
            $table->string('session');
            $table->string('invoice_no');
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
        Schema::dropIfExists('invoices');
    }
};
