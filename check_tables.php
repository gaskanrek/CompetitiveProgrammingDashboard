<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = Illuminate\Foundation\Application::getInstance();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if nim column exists in users table
try {
    $users = DB::table('users')->select('nim')->first();
    echo "nim column exists in users table" . PHP_EOL;
} catch (Exception $e) {
    echo "nim column does NOT exist in users table: " . $e->getMessage() . PHP_EOL;
}

// Check user_profiles table
try {
    $profiles = DB::table('user_profiles')->select('nim')->first();
    echo "nim column exists in user_profiles table" . PHP_EOL;
} catch (Exception $e) {
    echo "user_profiles table does NOT exist or nim column missing: " . $e->getMessage() . PHP_EOL;
}

// Check what columns exist in users table
try {
    $columns = DB::getSchemaBuilder()->getColumnListing('users');
    echo "Users table columns: " . implode(', ', $columns) . PHP_EOL;
} catch (Exception $e) {
    echo "Error getting users columns: " . $e->getMessage() . PHP_EOL;
}

// Check if user_profiles table exists
try {
    $profileColumns = DB::getSchemaBuilder()->getColumnListing('user_profiles');
    echo "User_profiles table columns: " . implode(', ', $profileColumns) . PHP_EOL;
} catch (Exception $e) {
    echo "Error getting user_profiles columns: " . $e->getMessage() . PHP_EOL;
}
