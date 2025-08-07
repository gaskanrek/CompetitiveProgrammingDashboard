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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codeforces_handle_id')->constrained()->onDelete('cascade');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->bigInteger('submission_id')->unique();
            $table->timestamp('creation_time');
            $table->string('author_handle');
            $table->string('programming_language');
            $table->enum('verdict', [
                'OK', 'WRONG_ANSWER', 'TIME_LIMIT_EXCEEDED', 'MEMORY_LIMIT_EXCEEDED',
                'COMPILATION_ERROR', 'RUNTIME_ERROR', 'PRESENTATION_ERROR',
                'CHALLENGED', 'SKIPPED', 'TESTING', 'REJECTED'
            ]);
            $table->string('testset')->default('TESTS');
            $table->integer('passed_test_count')->default(0);
            $table->integer('time_consumed_millis')->default(0);
            $table->integer('memory_consumed_bytes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
