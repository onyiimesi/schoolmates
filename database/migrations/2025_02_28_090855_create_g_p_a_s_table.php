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
        if (!Schema::hasTable('g_p_a_s')) {
            Schema::create('g_p_a_s', function (Blueprint $table) {
                $table->id();
                $table->string('sch_id');
                $table->string('campus');
                $table->integer('min_mark')->comment('Minimum mark range');
                $table->integer('max_mark')->comment('Maximum mark range');
                $table->string('remark')->comment('Grade remark');
                $table->decimal('grade_point', 3, 2)->comment('Grade point value');
                $table->string('key_range')->nullable()->comment('Grade key range');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_p_a_s');
    }
};
