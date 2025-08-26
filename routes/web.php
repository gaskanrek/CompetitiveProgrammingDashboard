<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PendampingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Mahasiswa Routes
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/profile', [MahasiswaController::class, 'profile'])->name('profile');
        Route::post('/codeforces-handle', [MahasiswaController::class, 'updateCodeforcesHandle'])->name('codeforces.update');
        Route::post('/refresh-data', [MahasiswaController::class, 'refreshData'])->name('refresh');
        Route::get('/activity', [MahasiswaController::class, 'activity'])->name('activity');
        Route::get('/recommendations', [MahasiswaController::class, 'recommendations'])->name('recommendations');
        Route::get('/contests', [MahasiswaController::class, 'contests'])->name('contests');
    });
    
    // Pendamping Routes
    Route::prefix('pendamping')->name('pendamping.')->group(function () {
        Route::get('/mahasiswa', [PendampingController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/mahasiswa/{id}', [PendampingController::class, 'mahasiswaDetail'])->name('mahasiswa.detail');
        Route::post('/mahasiswa/{id}/refresh', [PendampingController::class, 'refreshUserData'])->name('mahasiswa.refresh');
        Route::get('/leaderboard', [PendampingController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/activities', [PendampingController::class, 'activities'])->name('activities');
        Route::get('/contests', [PendampingController::class, 'contests'])->name('contests');
        Route::post('/contests/{contest}/important', [PendampingController::class, 'toggleContestImportant'])->name('contests.important');
        Route::get('/settings', [PendampingController::class, 'settings'])->name('settings');
        Route::post('/settings/profile', [PendampingController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/password', [PendampingController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings/preferences', [PendampingController::class, 'updatePreferences'])->name('settings.preferences');
        Route::delete('/settings/delete', [PendampingController::class, 'deleteAccount'])->name('settings.delete');
        Route::post('/refresh-all', [PendampingController::class, 'refreshAllData'])->name('refresh.all');
        Route::post('/refresh-batch', [PendampingController::class, 'refreshBatchData'])->name('refresh.batch');
    });
});

// Welcome/Landing page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
