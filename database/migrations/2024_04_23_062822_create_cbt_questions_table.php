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
        if (!Schema::hasTable('cbt_questions')) {
            Schema::create('cbt_questions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cbt_setting_id');
                $table->string('teacher_id')->nullable();
                $table->string('sch_id');
                $table->string('campus');
                $table->string('period');
                $table->string('term');
                $table->string('session');
                $table->string('subject_id');
                $table->string('question_type');
                $table->string('question');
                $table->string('option1');
                $table->string('option2');
                $table->string('option3');
                $table->string('option4');
                $table->string('answer');
                $table->string('question_mark');
                $table->string('total_mark')->nullable();
                $table->string('question_number')->nullable();
                $table->string('total_question')->nullable();
                $table->string('status')->nullable();

                $table->index(['cbt_setting_id', 'subject_id']);

                $table->foreign('cbt_setting_id')->references('id')->on('cbt_settings')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_questions');
    }
};
