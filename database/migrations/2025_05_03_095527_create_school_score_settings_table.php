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
        if (!Schema::hasTable('school_score_settings')) {
            Schema::create('school_score_settings', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->unsignedBigInteger('score_option_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_score_settings');
    }
};
