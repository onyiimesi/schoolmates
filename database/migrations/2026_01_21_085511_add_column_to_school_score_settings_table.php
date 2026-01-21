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
        Schema::table('school_score_settings', function (Blueprint $table) {
            $table->integer('previous_score_option_id')->nullable()->after('value_score');
            $table->string('previous_value_score')->nullable()->after('previous_score_option_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_score_settings', function (Blueprint $table) {
            $table->dropColumn(['previous_score_option_id', 'previous_value_score']);
        });
    }
};
