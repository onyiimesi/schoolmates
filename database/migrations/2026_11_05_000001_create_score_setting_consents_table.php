<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores the signed consent letter uploaded whenever a school's score
     * setting is changed (managed from the admin portal). Changing the score
     * setting affects how previously recorded results are viewed, so the
     * school agrees to forfeit them.
     */
    public function up(): void
    {
        if (!Schema::hasTable('score_setting_consents')) {
            Schema::create('score_setting_consents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_score_setting_id')->index();
                $table->string('sch_id')->index();
                $table->string('campus');
                $table->unsignedBigInteger('previous_score_option_id')->nullable();
                $table->string('previous_value_score')->nullable();
                $table->unsignedBigInteger('new_score_option_id');
                $table->string('new_value_score')->nullable();
                $table->string('letter_disk')->default('local');
                $table->string('letter_path');
                $table->string('letter_file_id')->nullable();
                $table->string('letter_original_name')->nullable();
                $table->string('letter_mime')->nullable();
                $table->unsignedBigInteger('letter_size')->nullable();
                $table->timestamp('agreed_at')->nullable();
                $table->unsignedBigInteger('changed_by_admin_id')->nullable();
                $table->timestamps();

                $table->index(['sch_id', 'campus']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_setting_consents');
    }
};
