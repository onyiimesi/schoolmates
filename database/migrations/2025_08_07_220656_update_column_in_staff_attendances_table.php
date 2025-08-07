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
        Schema::table('staff_attendances', function (Blueprint $table) {
            $table->string('time_in')->nullable()->change();
            $table->string('time_out')->nullable()->change();
            $table->string('date_in')->nullable()->change();
            $table->string('date_out')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_attendances', function (Blueprint $table) {
            $table->string('time_in')->change();
            $table->string('time_out')->change();
            $table->string('date_in')->change();
            $table->string('date_out')->change();
        });
    }
};
