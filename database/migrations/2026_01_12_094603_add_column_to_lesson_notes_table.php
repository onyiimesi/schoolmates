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
        Schema::table('lesson_notes', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->longText('sub_topic')->nullable()->after('topic');
            $table->longText('specific_objectives')->nullable()->after('sub_topic');
            $table->longText('previous_lesson')->nullable()->after('specific_objectives');
            $table->longText('previous_knowledge')->nullable()->after('previous_lesson');
            $table->longText('set_induction')->nullable()->after('previous_knowledge');
            $table->longText('methodology')->nullable()->after('set_induction');
            $table->longText('teaching_aid')->nullable()->after('methodology');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_notes', function (Blueprint $table) {
            $table->dropColumn(['date', 'sub_topic', 'specific_objectives', 'previous_lesson', 'previous_knowledge', 'set_induction', 'methodology', 'teaching_aid']);
        });
    }
};
