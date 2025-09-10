# Telegram Integration Documentation

## Setup Telegram Bot

1. **Buat Bot Telegram**
   - Chat dengan @BotFather di Telegram
   - Ketik `/newbot` dan ikuti instruksi
   - Simpan bot token yang diberikan

2. **Buat Channel Telegram**
   - Buat channel public atau private
   - Tambahkan bot sebagai admin channel
   - Dapatkan chat_id channel

3. **Konfigurasi Environment**
   ```
   TELEGRAM_BOT_TOKEN=your_bot_token_here
   TELEGRAM_CHAT_ID=your_chat_id_here
   ```

## Cara Kerja Sistem

1. **Forward Messages**
   - Forward pesan yang berkaitan dengan contest ke channel
   - Bot akan otomatis mendeteksi pesan yang mengandung keyword contest-related
   - Keywords: contest, kontes, competition, kompetisi, round, div, educational, global, codeforces, atcoder, topcoder, leetcode

2. **Display di Dashboard**
   - Pesan disimpan permanen di database
   - Tampil konsisten tanpa cache expiry
   - Tombol refresh manual untuk fetch pesan baru

## Database Storage

### Permanent Storage
- Pesan disimpan ke database table `telegram_messages`
- **Pesan TIDAK akan hilang** kecuali dihapus manual
- Sistem otomatis deteksi duplicate (berdasarkan telegram_message_id)

### Message Status
- `is_active`: Status aktif/nonaktif (bisa di-hide manual)
- `is_contest_related`: Auto-set true untuk pesan yang match keyword
- `expires_at`: Auto-expire date (default: 7 hari dari tanggal pesan)

### Auto-Expire System ⏰
✅ **Smart Expiry** - Pesan otomatis hilang setelah 7 hari (konfigurasi)
✅ **Like Contest System** - Mirip kontes yang hilang setelah selesai
✅ **Scheduled Task** - Auto-expire berjalan setiap jam
✅ **Configurable** - Durasi expire bisa diatur via `TELEGRAM_EXPIRY_DAYS`

### Keuntungan Auto-Expire
- **Fresh Content** - Pesan lama otomatis hilang, konten selalu relevan
- **No Manual Management** - Tidak perlu hapus manual
- **Performance** - Database tidak menumpuk pesan lama
- **User Experience** - Hanya info penting yang ditampilkan

### Keuntungan Database Storage
✅ **Permanent** - Pesan tidak hilang saat server restart
✅ **No Cache Expiry** - Tampil konsisten tanpa refresh
✅ **Better Performance** - Query database lebih cepat
✅ **Management** - Bisa hide/show pesan manual
✅ **History** - Semua pesan tersimpan untuk referensi

## Commands

- `php artisan telegram:test` - Test koneksi bot
- `php artisan telegram:fetch` - Fetch dan simpan pesan baru ke database
- `php artisan telegram:status` - Lihat status database dan pesan tersimpan
- `php artisan telegram:expire` - Manual expire pesan yang sudah lewat waktu
- `php artisan telegram:cleanup --days=30` - Hapus pesan lama (30 hari)
- `php artisan telegram:demo-expire` - Demo expire functionality

## Features

1. **Auto-detection Contest Messages**
   - Sistem otomatis filter pesan yang berkaitan dengan contest
   - Menampilkan link jika ada di pesan
   - Menampilkan info forward jika pesan di-forward

2. **Cache System**
   - Cache 5 menit untuk mengurangi API calls
   - Manual refresh via button untuk pendamping
   - Auto-refresh bisa dijadwalkan via cron

3. **Error Handling**
   - Graceful fallback jika Telegram tidak tersedia
   - Logging error untuk debugging
   - Tidak mengganggu fungsi utama dashboard

## Usage Tips

1. **Forward relevant messages**
   - Forward announcement contest dari official channels
   - Forward tips dan info penting tentang contest
   - Gunakan keywords yang relevan agar terdeteksi sistem

2. **Monitor**
   - Check log jika pesan tidak muncul
   - Pastikan bot masih admin di channel
   - Test koneksi berkala dengan command

## Troubleshooting

1. **Messages not showing**
   - Check bot token dan chat_id di .env
   - Pastikan bot adalah admin channel
   - Check dengan `php artisan telegram:test`

2. **Cache issues**
   - Clear cache: `php artisan cache:clear`
   - Manual refresh via dashboard button
   - Check cache duration di config

3. **API Errors**
   - Check internet connection
   - Verify bot token masih valid
   - Check rate limiting
