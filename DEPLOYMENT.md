# 🚀 Panduan Deployment - CP Dashboard

## 📋 Ringkasan Proyek

Dashboard Competitive Programming telah berhasil dibuat dengan fitur lengkap:

### ✅ Fitur yang Telah Diimplementasi:
- **Authentication System**: Role-based (Mahasiswa/Pendamping)
- **Database Structure**: 9 tabel dengan relasi lengkap
- **Codeforces Integration**: API crawling dengan rate limiting
- **Dashboard Mahasiswa**: Profile, aktivitas, rekomendasi soal
- **Dashboard Pendamping**: Monitoring, leaderboard, analytics
- **Scheduled Tasks**: Auto-crawling harian/mingguan
- **Data Population**: 2020+ contests, 10555+ problems dari Codeforces

### 📊 Status Data:
```
✅ Users: 4 (termasuk sample accounts)
✅ Contests: 2,020 (real data dari Codeforces)
✅ Problems: 10,555 (real data dari Codeforces)
✅ All routes: 28 routes registered
```

## 🎯 Akun untuk Testing

### Pendamping (Mentor)
- **Email**: `pendamping@test.com`
- **Password**: `password`
- **Features**: Monitor mahasiswa, leaderboard, bulk refresh

### Mahasiswa (Students)
- **Ahmad**: `ahmad@test.com` / `password`
- **Siti**: `siti@test.com` / `password`
- **Doni**: `doni@test.com` / `password`

## 🌐 Deployment ke Production

### 1. Persiapan Server

**Requirements:**
- PHP 8.2+
- MySQL 8.0+
- Apache/Nginx
- Composer
- Node.js & NPM

### 2. Setup Production Environment

```bash
# 1. Upload files ke server
scp -r dashboard/ user@server:/var/www/html/

# 2. SSH ke server
ssh user@server

# 3. Navigate ke project
cd /var/www/html/dashboard

# 4. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 5. Set permissions
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
```

### 3. Environment Production

```bash
# Copy environment
cp .env.example .env.production
nano .env.production
```

**Production .env:**
```env
APP_NAME="CP Dashboard"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cp_dashboard_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_strong_password

# Generate new key
APP_KEY=base64:your_production_key

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### 4. Database Production

```bash
# Create production database
mysql -u root -p
CREATE DATABASE cp_dashboard_prod;
CREATE USER 'cp_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON cp_dashboard_prod.* TO 'cp_user'@'localhost';
FLUSH PRIVILEGES;
exit;

# Run migrations
php artisan migrate --env=production
php artisan db:seed --env=production

# Crawl initial data
php artisan codeforces:crawl-contests
```

### 5. Web Server Configuration

**Apache (.htaccess sudah ada di public/):**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/dashboard/public
    
    <Directory /var/www/html/dashboard/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/cp-dashboard_error.log
    CustomLog ${APACHE_LOG_DIR}/cp-dashboard_access.log combined
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/html/dashboard/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. SSL Certificate (Recommended)

```bash
# Using Certbot for Let's Encrypt
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

### 7. Scheduler Setup

```bash
# Add to crontab
sudo crontab -e

# Add this line:
* * * * * cd /var/www/html/dashboard && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Optimization

```bash
# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## 🔧 Maintenance

### Daily Tasks
```bash
# Check logs
tail -f storage/logs/laravel.log

# Manual crawling jika diperlukan
php artisan codeforces:crawl-all

# Check disk space
df -h
```

### Weekly Tasks
```bash
# Backup database
mysqldump -u cp_user -p cp_dashboard_prod > backup_$(date +%Y%m%d).sql

# Clear old logs
php artisan log:clear

# Update dependencies (careful!)
composer update
```

### Monthly Tasks
```bash
# Check for security updates
composer audit

# Performance review
php artisan optimize:clear
```

## 📊 Monitoring

### Log Files
- **Application**: `storage/logs/laravel.log`
- **Crawler**: `storage/logs/crawler.log`
- **Web Server**: `/var/log/apache2/` or `/var/log/nginx/`

### Health Checks
```bash
# Application status
curl -I http://yourdomain.com

# Database connection
php artisan migrate:status

# Scheduler status
php artisan schedule:list
```

## 🚨 Troubleshooting

### Common Issues

**1. Permission Errors**
```bash
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

**2. 500 Internal Server Error**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**3. Database Connection Failed**
```bash
# Test connection
php artisan tinker
DB::connection()->getPdo();
```

**4. Codeforces API Issues**
```bash
# Check rate limiting
tail -f storage/logs/crawler.log

# Manual test
php artisan codeforces:crawl-user tourist
```

## 🔐 Security Checklist

- [ ] Change default passwords
- [ ] Update APP_KEY for production
- [ ] Enable HTTPS/SSL
- [ ] Set APP_DEBUG=false
- [ ] Configure firewall (UFW/iptables)
- [ ] Regular backups
- [ ] Update dependencies regularly
- [ ] Monitor access logs

## 📈 Performance Tips

1. **Database Indexing**: Sudah diimplementasi pada field yang sering diquery
2. **Caching**: Gunakan Redis untuk session dan cache jika traffic tinggi
3. **CDN**: Gunakan CDN untuk static assets
4. **Queue**: Implementasi queue untuk crawling tasks yang berat

## 🎉 Next Steps

1. **Test semua fitur** dengan akun yang telah disediakan
2. **Add real users** dan handle Codeforces mereka
3. **Monitor crawling** berjalan dengan baik
4. **Customize styling** sesuai branding institusi
5. **Add more features** sesuai kebutuhan

---

**Dashboard CP siap untuk production! 🚀**

**Happy Competitive Programming! 💻🏆**
