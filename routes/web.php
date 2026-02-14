<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\ItemMatchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Lost Items
    Route::resource('lost-items', LostItemController::class);
    Route::get('/my-lost-items', [LostItemController::class, 'myItems'])->name('lost-items.my-items');
    
    // Found Items
    Route::resource('found-items', FoundItemController::class);
    Route::get('/my-found-items', [FoundItemController::class, 'myItems'])->name('found-items.my-items');
    
    // Matches
    Route::resource('matches', ItemMatchController::class)->only(['index', 'show']);
    Route::get('/my-matches', [ItemMatchController::class, 'myMatches'])->name('matches.my-matches');
    Route::post('/matches/{match}/confirm', [ItemMatchController::class, 'confirmMatch'])->name('matches.confirm');
    Route::post('/matches/{match}/reject', [ItemMatchController::class, 'rejectMatch'])->name('matches.reject');
    
    // Map
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/items-in-bounds', [MapController::class, 'getItems'])->name('api.items-in-bounds');
});
// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    
    Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    // ... other admin routes
});
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
});