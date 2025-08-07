#!/bin/bash

# CP Dashboard Setup Script
# Script untuk setup dashboard CP Laravel dengan mudah

echo "🏆 CP Dashboard - Setup Script"
echo "================================"

# Function to print colored output
print_status() {
    echo -e "\033[1;32m[INFO]\033[0m $1"
}

print_error() {
    echo -e "\033[1;31m[ERROR]\033[0m $1"
}

print_warning() {
    echo -e "\033[1;33m[WARNING]\033[0m $1"
}

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer not found. Please install composer first."
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    print_error "PHP not found. Please install PHP 8.2+ first."
    exit 1
fi

print_status "Starting CP Dashboard setup..."

# Install Composer dependencies
print_status "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Check if .env exists
if [ ! -f .env ]; then
    print_status "Creating .env file..."
    cp .env.example .env
fi

# Generate application key
print_status "Generating application key..."
php artisan key:generate

# Database setup
echo ""
echo "Database Configuration:"
echo "1. MySQL (recommended for production)"
echo "2. SQLite (good for development/testing)"
read -p "Choose database (1 or 2): " db_choice

if [ "$db_choice" == "1" ]; then
    print_status "MySQL selected. Please configure your .env file with MySQL credentials."
    print_warning "Make sure to:"
    echo "  - Create database: CREATE DATABASE cp_dashboard;"
    echo "  - Update DB_* variables in .env file"
    echo "  - Run: php artisan migrate --seed"
elif [ "$db_choice" == "2" ]; then
    print_status "SQLite selected. Setting up SQLite database..."
    
    # Update .env for SQLite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    sed -i 's/DB_HOST=127.0.0.1/#DB_HOST=127.0.0.1/' .env
    sed -i 's/DB_PORT=3306/#DB_PORT=3306/' .env
    sed -i 's/DB_DATABASE=cp_dashboard/#DB_DATABASE=cp_dashboard/' .env
    sed -i 's/DB_USERNAME=root/#DB_USERNAME=root/' .env
    sed -i 's/DB_PASSWORD=/#DB_PASSWORD=/' .env
    
    # Create SQLite database file
    touch database/database.sqlite
    
    # Run migrations
    print_status "Running migrations and seeders..."
    php artisan migrate --seed
fi

# Install Node.js dependencies and build assets
if command -v npm &> /dev/null; then
    print_status "Installing Node.js dependencies..."
    npm install
    
    print_status "Building frontend assets..."
    npm run build
else
    print_warning "npm not found. Please install Node.js and run:"
    echo "  npm install && npm run build"
fi

# Set up storage link
print_status "Creating storage link..."
php artisan storage:link

# Set permissions (Linux/Mac only)
if [[ "$OSTYPE" != "msys" && "$OSTYPE" != "win32" ]]; then
    print_status "Setting up permissions..."
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
fi

echo ""
print_status "Setup completed! 🎉"
echo ""
echo "Next steps:"
echo "1. Configure your .env file if needed"
echo "2. Run: php artisan serve"
echo "3. Visit: http://localhost:8000"
echo "4. Register new account and start using!"
echo ""
echo "Useful commands:"
echo "  php artisan codeforces:crawl-contests  # Crawl contests"
echo "  php artisan codeforces:crawl-user {handle}  # Crawl specific user"
echo "  php artisan codeforces:crawl-all  # Crawl all users"
echo ""
print_status "Happy Competitive Programming! 🚀"
