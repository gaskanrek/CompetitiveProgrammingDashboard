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
        Schema::create('codeforces_handles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('handle')->unique();
            $table->integer('current_rating')->default(0);
            $table->integer('max_rating')->default(0);
            $table->string('rank')->nullable();
            $table->string('max_rank')->nullable();
            $table->integer('contribution')->default(0);
            $table->string('organization')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('avatar_url')->nullable();
            $table->timestamp('last_crawled_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codeforces_handles');
    }
};
