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
    
    // Lost Items with approval workflow
    Route::resource('lost-items', LostItemController::class);
    Route::get('/my-lost-items', [LostItemController::class, 'myItems'])->name('lost-items.my-items');
    
    // Admin approval routes for lost items
    Route::prefix('lost-items')->name('lost-items.')->group(function () {
        Route::post('/{lostItem}/approve', [LostItemController::class, 'approve'])
            ->name('approve')
            ->middleware('can:approve,App\Models\LostItem');
        Route::post('/{lostItem}/reject', [LostItemController::class, 'reject'])
            ->name('reject')
            ->middleware('can:reject,App\Models\LostItem');
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
            ->middleware('can:approve,App\Models\FoundItem');
        Route::post('/{foundItem}/reject', [FoundItemController::class, 'reject'])
            ->name('reject')
            ->middleware('can:reject,App\Models\FoundItem');
        Route::post('/bulk-approve', [FoundItemController::class, 'bulkApprove'])
            ->name('bulk-approve')
            ->middleware('can:bulkApprove,App\Models\FoundItem');
        Route::get('/pending-count', [FoundItemController::class, 'getPendingCount'])
            ->name('pending-count');
    });
    
    // Matches
    Route::resource('matches', ItemMatchController::class)->only(['index', 'show']);
    Route::get('/my-matches', [ItemMatchController::class, 'myMatches'])->name('matches.my-matches');
    Route::post('/matches/{match}/confirm', [ItemMatchController::class, 'confirmMatch'])->name('matches.confirm');
    Route::post('/matches/{match}/reject', [ItemMatchController::class, 'rejectMatch'])->name('matches.reject');
    
    // Map
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/items-in-bounds', [MapController::class, 'getItems'])->name('api.items-in-bounds');
   
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/start/{user}', [MessageController::class, 'start'])->name('messages.start');
    
    // API routes for real-time features
    Route::get('/api/messages/unread-count', [MessageController::class, 'getUnreadCount'])->name('api.messages.unread');
    Route::get('/api/messages/recent', [MessageController::class, 'getRecentMessages'])->name('api.messages.recent');
    Route::get('/api/messages/poll', [MessageController::class, 'pollNewMessages'])->name('api.messages.poll');
    Route::post('/api/messages/{conversation}/read', [MessageController::class, 'markAsRead'])->name('api.messages.read');
    
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
    
    // Users Management - Full CRUD
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
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
| API Routes for Search (Accessible to authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread');
    Route::get('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

/*
|--------------------------------------------------------------------------
| Debug Routes (Remove in production)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::middleware('auth')->prefix('debug')->name('debug.')->group(function () {
        Route::get('/check-admin', function() {
            return response()->json([
                'is_admin' => Auth::user()->isAdmin(),
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'user_role' => Auth::user()->role
            ]);
        });
        
        Route::get('/check-found-policy/{foundItem}', function(App\Models\FoundItem $foundItem) {
            return response()->json([
                'can_approve' => Gate::allows('approve', $foundItem),
                'can_reject' => Gate::allows('reject', $foundItem),
                'is_admin' => Auth::user()->isAdmin(),
                'item_status' => $foundItem->status,
                'item_user_id' => $foundItem->user_id,
                'item_id' => $foundItem->id
            ]);
        });
        
        Route::get('/check-lost-policy/{lostItem}', function(App\Models\LostItem $lostItem) {
            return response()->json([
                'can_approve' => Gate::allows('approve', $lostItem),
                'can_reject' => Gate::allows('reject', $lostItem),
                'is_admin' => Auth::user()->isAdmin(),
                'item_status' => $lostItem->status,
                'item_user_id' => $lostItem->user_id,
                'item_id' => $lostItem->id
            ]);
        });
        
        Route::get('/phpinfo', function() {
            phpinfo();
        })->middleware('admin'); // Only admins can see phpinfo
    });
}

/*
|--------------------------------------------------------------------------
| Test Routes (For development only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test-mail', function() {
        return view('test-mail');
    });
}