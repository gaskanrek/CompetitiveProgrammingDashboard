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
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->integer('contest_id')->unique();
            $table->string('name');
            $table->string('type')->default('CF'); // CF = Codeforces
            $table->enum('phase', ['BEFORE', 'CODING', 'PENDING_SYSTEM_TEST', 'SYSTEM_TEST', 'FINISHED']);
            $table->boolean('frozen')->default(false);
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('relative_time_seconds')->nullable();
            $table->integer('prepared_by_id')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('difficulty')->nullable();
            $table->string('kind')->nullable();
            $table->string('icpc_region')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('season')->nullable();
            $table->boolean('is_important')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
