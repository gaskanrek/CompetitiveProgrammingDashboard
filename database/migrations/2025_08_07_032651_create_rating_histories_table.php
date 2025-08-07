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
        Schema::create('rating_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codeforces_handle_id')->constrained()->onDelete('cascade');
            $table->foreignId('contest_id')->constrained()->onDelete('cascade');
            $table->string('contest_name');
            $table->integer('old_rating');
            $table->integer('new_rating');
            $table->integer('rank');
            $table->timestamp('rating_update_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_histories');
    }
};
