<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LostItemController;
use App\Http\Controllers\Api\FoundItemController;
use App\Http\Controllers\Api\ItemMatchController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProfileController;

// ==================== PUBLIC API ROUTES ====================

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Test Routes
Route::get('/test', function () {
    return response()->json([
        'message'   => 'API is working!',
        'timestamp' => now(),
        'status'    => 'ok',
        'version'   => '1.0.0'
    ]);
});

Route::get('/health', function () {
    return response()->json([
        'status'          => 'healthy',
        'timestamp'       => now(),
        'php_version'     => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

// ==================== PROTECTED API ROUTES ====================

Route::middleware('auth:sanctum')->group(function () {

    // ========== AUTHENTICATION ==========
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // ========== USER PROFILE ==========
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto']);
    Route::get('/profile/stats', [ProfileController::class, 'getStats']);
    Route::get('/profile/activity', [ProfileController::class, 'getActivity']);

    // Legacy UserController profile routes
    Route::get('/user/profile', [UserController::class, 'me']);
    Route::put('/user/profile', [UserController::class, 'update']);
    Route::post('/profile/update', [UserController::class, 'update']);
    Route::get('/profile/items', [UserController::class, 'items']);
    Route::get('/profile/matches', [UserController::class, 'matches']);

    // ========== USERS ==========
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/search', [UserController::class, 'search']);

    // ========== DASHBOARD ==========
    Route::get('/dashboard/data', [DashboardController::class, 'dashboardData']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-items', [DashboardController::class, 'recentItems']);

    // ========== LOST ITEMS ==========
    Route::get('/lost-items/my-items', [LostItemController::class, 'myItems']);
    Route::get('/lost-items/pending/count', [LostItemController::class, 'getPendingCount']);
    Route::post('/lost-items/bulk-approve', [LostItemController::class, 'bulkApprove']);
    Route::get('/lost-items', [LostItemController::class, 'index']);
    Route::post('/lost-items', [LostItemController::class, 'store']);
    Route::get('/lost-items/{lostItem}', [LostItemController::class, 'show']);
    Route::put('/lost-items/{lostItem}', [LostItemController::class, 'update']);
    Route::delete('/lost-items/{lostItem}', [LostItemController::class, 'destroy']);
    Route::post('/lost-items/{lostItem}/approve', [LostItemController::class, 'approve']);
    Route::post('/lost-items/{lostItem}/reject', [LostItemController::class, 'reject']);
    // ADDED: Mark as found endpoint
    Route::post('/lost-items/{id}/mark-as-found', [LostItemController::class, 'markAsFound']);

    // ========== FOUND ITEMS ==========
    Route::get('/found-items/my-items', [FoundItemController::class, 'myItems']);
    Route::get('/found-items/pending/count', [FoundItemController::class, 'getPendingCount']);
    Route::post('/found-items/bulk-approve', [FoundItemController::class, 'bulkApprove']);
    Route::get('/found-items', [FoundItemController::class, 'index']);
    Route::post('/found-items', [FoundItemController::class, 'store']);
    Route::get('/found-items/{foundItem}', [FoundItemController::class, 'show']);
    Route::put('/found-items/{foundItem}', [FoundItemController::class, 'update']);
    Route::delete('/found-items/{foundItem}', [FoundItemController::class, 'destroy']);
    Route::post('/found-items/{foundItem}/approve', [FoundItemController::class, 'approve']);
    Route::post('/found-items/{foundItem}/reject', [FoundItemController::class, 'reject']);
    // ADDED: Mark as claimed endpoint
    Route::post('/found-items/{id}/mark-as-claimed', [FoundItemController::class, 'markAsClaimed']);

    // ========== MATCHES ==========
    Route::get('/matches/my-matches', [ItemMatchController::class, 'myMatches']);
    Route::get('/matches/my-stats', [ItemMatchController::class, 'getMyMatchStats']);
    Route::get('/matches/pending/count', [ItemMatchController::class, 'getPendingCount']);
    Route::post('/matches/bulk-update', [ItemMatchController::class, 'bulkUpdate']);
    Route::get('/matches', [ItemMatchController::class, 'index']);
    Route::get('/matches/{match}', [ItemMatchController::class, 'show']);
    Route::post('/matches/{match}/confirm', [ItemMatchController::class, 'confirmMatch']);
    Route::post('/matches/{match}/reject', [ItemMatchController::class, 'rejectMatch']);

    // ========== MESSAGES ==========
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount']);
    Route::get('/messages/recent', [MessageController::class, 'getRecentMessages']);
    Route::post('/messages/start/{user}', [MessageController::class, 'start']);
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/messages/{conversation}', [MessageController::class, 'show']);
    Route::post('/messages/{conversation}/send', [MessageController::class, 'send']);
    Route::post('/messages/{conversation}/read', [MessageController::class, 'markAsRead']);
    Route::post('/messages/{conversation}/poll', [MessageController::class, 'pollNewMessages']);

    // ========== NOTIFICATIONS ==========
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'delete']);

    // ========== MAP ==========
    Route::get('/map/items', [MapController::class, 'getItems']);
    Route::get('/map/locations', [MapController::class, 'index']);

    // ========== ADMIN ROUTES ==========
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats']);

        // User Management
        Route::get('/users/recent', [AdminController::class, 'recentUsers']);
        Route::post('/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::post('/users', [AdminController::class, 'storeUser']);
        Route::get('/users/{user}', [AdminController::class, 'showUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword']);

        // Item Management
        Route::get('/items/lost', [AdminController::class, 'lostItems']);
        Route::get('/items/found', [AdminController::class, 'foundItems']);
        Route::get('/items/pending', [AdminController::class, 'pendingItems']);
        Route::post('/items/bulk-delete', [AdminController::class, 'bulkDeleteItems']);
        Route::get('/items', [AdminController::class, 'items']);

        // Match Management
        Route::get('/matches/pending', [AdminController::class, 'pendingMatches']);
        Route::post('/matches/bulk-update', [AdminController::class, 'bulkUpdateMatches']);
        Route::get('/matches', [AdminController::class, 'matches']);

        // Reports
        Route::get('/reports', [AdminController::class, 'reports']);
        Route::get('/export/users', [AdminController::class, 'exportUsers']);
        Route::get('/export/items', [AdminController::class, 'exportItems']);
        Route::get('/export/matches', [AdminController::class, 'exportMatches']);

        // Analytics
        Route::get('/analytics', [AdminController::class, 'analytics']);

        // Settings
        Route::get('/settings', [AdminController::class, 'settings']);
        Route::put('/settings', [AdminController::class, 'updateSettings']);
        Route::post('/settings/clear-cache', [AdminController::class, 'clearCache']);

        // Search
        Route::get('/search', [AdminController::class, 'search']);
        Route::get('/search/live', [AdminController::class, 'liveSearch']);
    });
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->json([
        'message' => 'API endpoint not found',
        'error'   => 'The requested route does not exist'
    ], 404);
});