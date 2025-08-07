# 🏆 CP Dashboard - Competitive Programming Dashboard

Dashboard interaktif untuk memantau aktivitas Competitive Programming mahasiswa dengan integrasi Codeforces.

## 🔥 Fitur Utama

### 👨‍🎓 Untuk Mahasiswa:
- ✅ Mendaftar dan login dengan role-based access
- ✅ Mendaftarkan handle Codeforces
- ✅ Melihat riwayat aktivitas (submissions, rating changes, contest participations)
- ✅ Rekomendasi soal berdasarkan rating dan progress
- ✅ Daftar kontes mendatang
- ✅ Manual refresh data pribadi

### 👨‍🏫 Untuk Pendamping:
- ✅ Dashboard monitoring semua mahasiswa
- ✅ Leaderboard berdasarkan rating dan progress
- ✅ Monitor aktivitas real-time
- ✅ Kelola kontes penting
- ✅ Bulk refresh data semua mahasiswa
- ✅ Statistik lengkap komunitas

### 🤖 Sistem Crawling:
- ✅ Otomatis crawl data dari Codeforces API
- ✅ Scheduled crawling (harian/mingguan)
- ✅ Manual refresh untuk data terbaru
- ✅ Rate limiting dan error handling
- ✅ Extensible untuk platform CP lain

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Blade Templates + Bootstrap 5
- **Database:** MySQL (SQLite untuk development)
- **UI Framework:** Bootstrap 5 + Bootstrap Icons
- **External API:** Codeforces API

## 📦 Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- MySQL/MariaDB (atau SQLite untuk development)
- Node.js & NPM (untuk build assets)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone [repository-url]
   cd dashboard
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   # Copy environment file
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

4. **Konfigurasi database**
   
   **Untuk MySQL:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cp_dashboard
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```
   
   **Untuk SQLite (development):**
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST, DB_PORT, DB_DATABASE, dll bisa di-comment
   ```

5. **Jalankan migrasi dan seeder**
   ```bash
   # Buat database (untuk MySQL)
   mysql -u root -p -e "CREATE DATABASE cp_dashboard;"
   
   # Jalankan migrasi
   php artisan migrate
   
   # Jalankan seeder
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

7. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

8. **Setup scheduler (opsional, untuk crawling otomatis)**
   
   Tambahkan ke crontab:
   ```bash
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   ```

## 🚀 Penggunaan

### Registrasi Pengguna

1. Akses `http://localhost:8000`
2. Klik "Daftar" dan pilih role:
   - **Mahasiswa**: Untuk peserta CP
   - **Pendamping**: Untuk mentor/pengawas

### Setup Handle Codeforces (Mahasiswa)

1. Login sebagai mahasiswa
2. Masuk ke "Profil & Handle"
3. Tambahkan handle Codeforces yang valid
4. Sistem akan otomatis crawl data Anda

### Crawling Data

**Manual:**
```bash
# Crawl contests dan problems
php artisan codeforces:crawl-contests

# Crawl data specific user
php artisan codeforces:crawl-user {handle}

# Crawl semua user terdaftar
php artisan codeforces:crawl-all
```

**Otomatis (via scheduler):**
- Contests: Setiap hari jam 02:00
- User data: Setiap Senin jam 03:00

## 📁 Struktur Proyek

```
dashboard/
├── app/
│   ├── Console/Commands/          # Crawling commands
│   ├── Http/Controllers/          # Controllers
│   │   ├── Auth/                 # Authentication
│   │   ├── DashboardController.php
│   │   ├── MahasiswaController.php
│   │   └── PendampingController.php
│   └── Models/                   # Eloquent models
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Data seeders
├── resources/views/              # Blade templates
│   ├── auth/                    # Login/Register
│   ├── dashboard/               # Dashboard views
│   ├── mahasiswa/               # Student views
│   ├── pendamping/              # Mentor views
│   └── layouts/                 # Layout templates
└── routes/
    ├── web.php                  # Web routes
    └── console.php              # Console routes & scheduler
```

## 🔧 Konfigurasi

### Environment Variables

```env
# Application
APP_NAME="CP Dashboard"
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=mysql
DB_DATABASE=cp_dashboard

# Codeforces API
CODEFORCES_API_URL=https://codeforces.com/api
CRAWLER_DELAY_SECONDS=1
CRAWLER_MAX_RETRIES=3
```

### Scheduled Tasks

Di `routes/console.php`:

```php
// Daily contest crawling
Schedule::command('codeforces:crawl-contests')
    ->dailyAt('02:00');

// Weekly user data crawling  
Schedule::command('codeforces:crawl-all')
    ->weeklyOn(1, '03:00');
```

## 🛣️ Roadmap & Pengembangan

### Phase 1 (Current) ✅
- [x] Basic authentication & role system
- [x] Codeforces integration
- [x] Dashboard untuk mahasiswa & pendamping
- [x] Crawling system dengan scheduler

### Phase 2 (Planned) 🔄
- [ ] Integration dengan platform CP lain (AtCoder, CodeChef)
- [ ] Social media monitoring (Twitter, Discord)
- [ ] Advanced analytics & charts
- [ ] Email notifications untuk kontes penting
- [ ] Export data ke PDF/Excel

### Phase 3 (Future) 📈
- [ ] Mobile responsive improvements
- [ ] Real-time notifications
- [ ] Team/group management
- [ ] Contest prediction & recommendations
- [ ] API untuk integrasi eksternal

## 🐛 Troubleshooting

### Common Issues

1. **Migration Error**
   ```bash
   # Reset database
   php artisan migrate:fresh --seed
   ```

2. **Codeforces API Rate Limit**
   - Increase `CRAWLER_DELAY_SECONDS` di .env
   - Implement exponential backoff

3. **Handle Not Found**
   - Pastikan handle Codeforces valid dan public
   - Check typo di input handle

4. **Scheduler Not Running**
   ```bash
   # Test scheduler manually
   php artisan schedule:run
   
   # Check crontab
   crontab -l
   ```

## 📊 Database Schema

### Tabel Utama:
- `users` - Data pengguna
- `roles` - Role system (mahasiswa/pendamping)
- `user_profiles` - Profile detail pengguna
- `codeforces_handles` - Handle Codeforces
- `contests` - Data kontes
- `problems` - Data soal
- `submissions` - Submission history
- `rating_histories` - Riwayat perubahan rating
- `contest_participations` - Partisipasi kontes

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📝 License

MIT License - see [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Your Name** - Initial work

## 🙏 Acknowledgments

- [Codeforces](https://codeforces.com) untuk API
- [Laravel](https://laravel.com) framework
- [Bootstrap](https://getbootstrap.com) untuk UI
- Community CP Indonesia

---

**Happy Competitive Programming! 🚀**

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
