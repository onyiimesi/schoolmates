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
        Schema::create('subject_teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id', 50);
            $table->string('campus', 100);
            $table->foreignId('subject_teacher_id')
                ->constrained('subject_teachers')
                ->cascadeOnDelete();
            $table->string('term', 20);
            $table->string('session', 20);
            $table->string('subject_name', 100);
            $table->integer('staff_id',);
            $table->integer('class_id');
            $table->timestamps();

            $table->unique(
                ['class_id', 'subject_name', 'sch_id', 'campus', 'term', 'session'],
                'unique_subject_class_assignment'
            );

            $table->index(['staff_id']);
            $table->index(['sch_id', 'campus', 'class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_teacher_subjects');
    }
};
