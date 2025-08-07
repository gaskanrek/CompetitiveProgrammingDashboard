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
        Schema::create('contest_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codeforces_handle_id')->constrained()->onDelete('cascade');
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->integer('rank');
            $table->integer('points')->default(0);
            $table->integer('penalty')->default(0);
            $table->integer('successful_hack_count')->default(0);
            $table->integer('unsuccessful_hack_count')->default(0);
            $table->timestamp('participation_time');
            $table->timestamps();
            
            $table->unique(['codeforces_handle_id', 'contest_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contest_participations');
    }
};
