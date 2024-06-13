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
        Schema::create('communication_books', function (Blueprint $table) {
            $table->id();
            $table->string('sch_id');
            $table->string('campus');
            $table->string('period');
            $table->string('term');
            $table->string('session');
            $table->bigInteger('class_id');
            $table->unsignedBigInteger('staff_id');
            $table->enum('status', ['active', 'closed'])->default('active');

            $table->index(['sch_id', 'campus', 'class_id', 'staff_id'], 'comm_book_idx');

            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('communication_book_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_book_id');
            $table->bigInteger('student_id');
            $table->string('admission_number');
            $table->string('subject');
            $table->longText('message');
            $table->boolean('pinned')->default(false);
            $table->string('file')->nullable();
            $table->string('file_name')->nullable();

            $table->index(['communication_book_id', 'student_id', 'admission_number'], 'comm_book_message_idx');

            $table->foreign('communication_book_id')->references('id')->on('communication_books')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('communication_book_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_book_id');
            $table->bigInteger('sender_id');
            $table->string('sender_type');
            $table->bigInteger('receiver_id');
            $table->string('receiver_type');
            $table->longText('message');
            $table->enum('status', ['read', 'unread'])->default('unread');

            $table->index(['communication_book_id', 'sender_id', 'receiver_id'], 'comm_book_reply_idx');

            $table->foreign('communication_book_id')->references('id')->on('communication_books')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_books');
        Schema::dropIfExists('communication_book_messages');
        Schema::dropIfExists('communication_book_replies');
    }
};
