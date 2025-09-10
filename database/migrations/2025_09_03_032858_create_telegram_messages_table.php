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
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_message_id')->unique(); // ID pesan dari Telegram
            $table->text('text'); // Isi pesan
            $table->timestamp('telegram_date'); // Tanggal pesan dari Telegram
            $table->string('chat_title')->nullable(); // Nama channel/chat
            $table->string('forward_from')->nullable(); // Jika pesan di-forward
            $table->json('entities')->nullable(); // URL, mention, dll
            $table->boolean('has_media')->default(false); // Ada foto/video/file
            $table->boolean('is_contest_related')->default(true); // Apakah terkait contest
            $table->boolean('is_active')->default(true); // Status aktif (bisa di-hide manual)
            $table->timestamp('expires_at')->nullable(); // Kapan pesan expire (optional)
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'is_contest_related', 'telegram_date'], 'tg_msg_active_contest_date_idx');
            $table->index('telegram_date', 'tg_msg_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
