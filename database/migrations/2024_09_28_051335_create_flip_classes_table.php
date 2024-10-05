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
        Schema::create('flip_classes', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('term');
            $table->string('session');
            $table->unsignedBigInteger('staff_id');
            $table->string('week');
            $table->integer('subject_id');
            $table->integer('class_id');
            $table->string('topic');
            $table->longText('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file');
            $table->string('file_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('submitted_by')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flip_classes');
    }
};
