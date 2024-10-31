<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_scan_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->unsignedBigInteger('staff_id');
            $table->string('time_in');
            $table->string('date_in');
            $table->string('time_out')->nullable();
            $table->string('date_out')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->longText('address')->nullable();
            $table->longText('location')->nullable();
            $table->enum('status', ['success', 'failed']);

            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_scan_attendances');
    }
};
