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
        Schema::create('slow_queries', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint', 64)->index();
            $table->string('connection')->nullable();
            $table->text('sql');
            $table->longText('raw_sql');
            $table->json('bindings')->nullable();
            $table->integer('time');
            $table->unsignedBigInteger('occurrences')->default(0);
            $table->integer('max_time')->default(0);
            $table->integer('avg_time')->default(0);
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('resolved')->default(false)->index();
            $table->timestamps();

            $table->index('last_seen_at');
            $table->index('avg_time');
            $table->index('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slow_queries');
    }
};
