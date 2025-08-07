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
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('problem_id'); // e.g., "1234A"
            $table->string('name');
            $table->string('index'); // A, B, C, etc.
            $table->integer('rating')->nullable();
            $table->json('tags')->nullable(); // JSON array of tags
            $table->integer('solved_count')->default(0);
            $table->string('type')->default('PROGRAMMING');
            $table->integer('points')->nullable();
            $table->text('problem_url')->nullable();
            $table->timestamps();
            
            $table->unique(['contest_id', 'index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
