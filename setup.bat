@echo off
rem CP Dashboard Setup Script for Windows
rem Script untuk setup dashboard CP Laravel dengan mudah

echo.
echo 🏆 CP Dashboard - Setup Script for Windows
echo ==========================================

echo [INFO] Starting CP Dashboard setup...

rem Check if composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [ERROR] Composer not found. Please install composer first.
    pause
    exit /b 1
)

rem Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [ERROR] PHP not found. Please install PHP 8.2+ first.
    pause
    exit /b 1
)

rem Install Composer dependencies
echo [INFO] Installing Composer dependencies...
composer install --no-dev --optimize-autoloader

rem Check if .env exists
if not exist .env (
    echo [INFO] Creating .env file...
    copy .env.example .env
)

rem Generate application key
echo [INFO] Generating application key...
php artisan key:generate

rem Database setup
echo.
echo Database Configuration:
echo 1. MySQL (recommended for production)
echo 2. SQLite (good for development/testing)
set /p db_choice="Choose database (1 or 2): "

if "%db_choice%"=="1" (
    echo [INFO] MySQL selected. Please configure your .env file with MySQL credentials.
    echo [WARNING] Make sure to:
    echo   - Create database: CREATE DATABASE cp_dashboard;
    echo   - Update DB_* variables in .env file
    echo   - Run: php artisan migrate --seed
) else if "%db_choice%"=="2" (
    echo [INFO] SQLite selected. Setting up SQLite database...
    
    rem Create SQLite database file
    if not exist database\database.sqlite (
        type nul > database\database.sqlite
    )
    
    rem Update .env for SQLite
    powershell -Command "(gc .env) -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_HOST=127.0.0.1', '#DB_HOST=127.0.0.1' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_PORT=3306', '#DB_PORT=3306' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_DATABASE=cp_dashboard', '#DB_DATABASE=cp_dashboard' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_USERNAME=root', '#DB_USERNAME=root' | Out-File -encoding ASCII .env"
    powershell -Command "(gc .env) -replace 'DB_PASSWORD=', '#DB_PASSWORD=' | Out-File -encoding ASCII .env"
    
    rem Run migrations
    echo [INFO] Running migrations and seeders...
    php artisan migrate --seed
)

rem Install Node.js dependencies and build assets
where npm >nul 2>nul
if %ERRORLEVEL% equ 0 (
    echo [INFO] Installing Node.js dependencies...
    npm install
    
    echo [INFO] Building frontend assets...
    npm run build
) else (
    echo [WARNING] npm not found. Please install Node.js and run:
    echo   npm install && npm run build
)

rem Set up storage link
echo [INFO] Creating storage link...
php artisan storage:link

echo.
echo [INFO] Setup completed! 🎉
echo.
echo Next steps:
echo 1. Configure your .env file if needed
echo 2. Run: php artisan serve
echo 3. Visit: http://localhost:8000
echo 4. Register new account and start using!
echo.
echo Useful commands:
echo   php artisan codeforces:crawl-contests  # Crawl contests
echo   php artisan codeforces:crawl-user {handle}  # Crawl specific user
echo   php artisan codeforces:crawl-all  # Crawl all users
echo.
echo [INFO] Happy Competitive Programming! 🚀
echo.
pause
