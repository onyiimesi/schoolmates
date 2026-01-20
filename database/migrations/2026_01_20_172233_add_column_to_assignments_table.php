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
        Schema::table('assignments', function (Blueprint $table) {
            $table->integer('subject_class_id')->nullable()->after('subject_id');
            $table->unsignedBigInteger('subject_id')->nullable()->change();

            $table->index('subject_class_id');
        });

        Schema::table('assignment_answers', function (Blueprint $table) {
            $table->integer('subject_class_id')->nullable()->after('subject_id');
            $table->integer('subject_id')->nullable()->change();

            $table->index('subject_class_id');
        });

        Schema::table('assignment_marks', function (Blueprint $table) {
            $table->integer('subject_class_id')->nullable()->after('subject_id');
            $table->integer('subject_id')->nullable()->change();

            $table->index('subject_class_id');
        });

        Schema::table('assignment_results', function (Blueprint $table) {
            $table->integer('subject_class_id')->nullable()->after('subject_id');
            $table->integer('subject_id')->nullable()->change();

            $table->index('subject_class_id');
        });

        Schema::table('assignment_performances', function (Blueprint $table) {
            $table->integer('subject_class_id')->nullable()->after('subject_id');
            $table->integer('subject_id')->nullable()->change();

            $table->index('subject_class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('subject_class_id');
            $table->unsignedBigInteger('subject_id')->change();
        });

        Schema::table('assignment_answers', function (Blueprint $table) {
            $table->dropColumn('subject_class_id');
            $table->integer('subject_id')->change();
        });

        Schema::table('assignment_marks', function (Blueprint $table) {
            $table->dropColumn('subject_class_id');
            $table->integer('subject_id')->change();
        });

        Schema::table('assignment_results', function (Blueprint $table) {
            $table->dropColumn('subject_class_id');
            $table->integer('subject_id')->change();
        });

        Schema::table('assignment_performances', function (Blueprint $table) {
            $table->dropColumn('subject_class_id');
            $table->integer('subject_id')->change();
        });
    }
};
