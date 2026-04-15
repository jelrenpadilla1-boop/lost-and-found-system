<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemMatchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Broadcast;

/*

|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Not Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ========== SEARCH ROUTES ==========
    // User search routes
    Route::get('/user/search', [SearchController::class, 'userSearch'])->name('user.search');
    Route::get('/user/search/live', [SearchController::class, 'userLiveSearch'])->name('user.search.live');
    
    // Admin search routes
    Route::get('/admin/search', [SearchController::class, 'adminSearch'])->name('admin.search');
    Route::get('/admin/search/live', [SearchController::class, 'adminLiveSearch'])->name('admin.search.live');
    // ===================================
    
    // Lost Items with approval workflow
    Route::resource('lost-items', LostItemController::class);
    Route::get('/my-lost-items', [LostItemController::class, 'myItems'])->name('lost-items.my-items');
    
    // Admin approval routes for lost items
    Route::prefix('lost-items')->name('lost-items.')->group(function () {
        Route::post('/{lostItem}/approve', [LostItemController::class, 'approve'])
            ->name('approve')
            ->middleware('can:approve,lostItem');
        Route::post('/{lostItem}/reject', [LostItemController::class, 'reject'])
            ->name('reject')
            ->middleware('can:reject,lostItem');
        Route::post('/bulk-approve', [LostItemController::class, 'bulkApprove'])
            ->name('bulk-approve')
            ->middleware('can:bulkApprove,App\Models\LostItem');
        Route::get('/pending-count', [LostItemController::class, 'getPendingCount'])
            ->name('pending-count');
    });
    
   // Found Items with approval workflow
Route::resource('found-items', FoundItemController::class);
Route::get('/my-found-items', [FoundItemController::class, 'myItems'])->name('found-items.my-items');

// Admin approval routes for found items
Route::prefix('found-items')->name('found-items.')->group(function () {
    Route::post('/{foundItem}/approve', [FoundItemController::class, 'approve'])
        ->name('approve')
        ->middleware('can:approve,foundItem');
    Route::post('/{foundItem}/reject', [FoundItemController::class, 'reject'])
        ->name('reject')
        ->middleware('can:reject,foundItem');
    Route::post('/bulk-approve', [FoundItemController::class, 'bulkApprove'])
        ->name('bulk-approve')
        ->middleware('can:bulkApprove,App\Models\FoundItem');
    Route::get('/pending-count', [FoundItemController::class, 'getPendingCount'])
        ->name('pending-count');
    
    // ========== CLAIM ROUTES ==========
    Route::post('/{foundItem}/claim', [FoundItemController::class, 'submitClaim'])
        ->name('claim');
    Route::post('/{foundItem}/claim/{claim}/approve', [FoundItemController::class, 'approveClaim'])
        ->name('claim.approve');
    Route::post('/{foundItem}/claim/{claim}/reject', [FoundItemController::class, 'rejectClaim'])
        ->name('claim.reject');
});
    // Matches
    Route::resource('matches', ItemMatchController::class)->only(['index', 'show']);
    Route::get('/my-matches', [ItemMatchController::class, 'myMatches'])->name('matches.my-matches');
    Route::post('/matches/{match}/confirm', [ItemMatchController::class, 'confirmMatch'])->name('matches.confirm');
    Route::post('/matches/{match}/reject', [ItemMatchController::class, 'rejectMatch'])->name('matches.reject');
    
    // Map
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/items-in-bounds', [MapController::class, 'getItems'])->name('api.items-in-bounds');
   
    // ========== MESSAGES ROUTES ==========
    // Main message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'send'])->name('messages.send');
    
    // ADD THIS ROUTE FOR PHOTO UPLOADS
    Route::post('/messages/{conversation}/send-photo', [MessageController::class, 'sendPhoto'])->name('messages.send-photo');
    
    Route::get('/messages/start/{user}', [MessageController::class, 'start'])->name('messages.start');
    Route::post('/messages/{conversation}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

    // Real-time messaging API routes
    Route::prefix('api/messages')->name('api.messages.')->group(function () {
        Route::get('/unread-count', [MessageController::class, 'getUnreadCount'])->name('unread');
        Route::get('/recent', [MessageController::class, 'getRecentMessages'])->name('recent');
        Route::post('/poll', [MessageController::class, 'pollNewMessages'])->name('poll');
        Route::post('/{conversation}/read', [MessageController::class, 'markAsRead'])->name('read');
        Route::post('/typing', [MessageController::class, 'typing'])->name('typing');
        Route::delete('/{message}', [MessageController::class, 'deleteMessage'])->name('delete');
        Route::get('/conversation/{conversation}', [MessageController::class, 'getConversationDetails'])->name('conversation');
        Route::get('/search/{conversation}', [MessageController::class, 'searchMessages'])->name('search');
        Route::get('/unread-conversations', [MessageController::class, 'getUnreadConversations'])->name('unread-conversations');
    });
    // =====================================
    
    // ========== NOTIFICATION ROUTES ==========
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Main view
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        
        // Mark as read routes
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        
        // Delete routes
        Route::delete('/{id}/delete', [NotificationController::class, 'delete'])->name('delete');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
        
        // API routes
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });
    // =========================================
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management - Full CRUD (CORRECT ORDER - specific routes BEFORE generic {user} routes)
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    
    // SPECIFIC routes first (these have additional segments after {user})
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
    
    // GENERIC {user} routes AFTER specific ones
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    
    // Bulk operations (no {user} parameter)
    Route::post('/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('users.bulk-delete');
    
    // Items Management (Admin overview)
    Route::get('/items', [AdminController::class, 'items'])->name('items.index');
    Route::get('/items/pending', [AdminController::class, 'pendingItems'])->name('items.pending');
    Route::get('/items/lost', [AdminController::class, 'lostItems'])->name('items.lost');
    Route::get('/items/found', [AdminController::class, 'foundItems'])->name('items.found');
    Route::post('/items/bulk-delete', [AdminController::class, 'bulkDeleteItems'])->name('items.bulk-delete');
    
    // Matches Management
    Route::get('/matches', [AdminController::class, 'matches'])->name('matches.index');
    Route::get('/matches/pending', [AdminController::class, 'pendingMatches'])->name('matches.pending');
    Route::post('/matches/bulk-update', [AdminController::class, 'bulkUpdateMatches'])->name('matches.bulk-update');
    
    // Reports & Analytics
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('export.users');
    Route::get('/export/items', [AdminController::class, 'exportItems'])->name('export.items');
    Route::get('/export/matches', [AdminController::class, 'exportMatches'])->name('export.matches');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/clear-cache', [AdminController::class, 'clearCache'])->name('settings.clear-cache');
});

/*
|--------------------------------------------------------------------------
| API Routes for Search & Notifications
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // Search API
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    
    // ========== NOTIFICATION API ROUTES ==========
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}/delete', [NotificationController::class, 'delete'])->name('delete');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
    });
    // ===========================================
});

/*
|--------------------------------------------------------------------------
| Broadcast Channel Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
});

/*
|--------------------------------------------------------------------------
| Test Routes (Development only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test-mail', function() {
        return view('test-mail');
    });
}