<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Models\User;
use App\Services\AIMatchingService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AIMatchingService::class, function ($app) {
            return new AIMatchingService();
        });
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $vars = [
                'unreadNotificationsCount' => 0,
                'totalUnread'              => 0,
                'sidebarLostBadge'         => 0,
                'sidebarFoundBadge'        => 0,
                'pendingMatchesCount'      => 0,
                'myLostBadge'              => 0,
                'myFoundBadge'             => 0,
                'myMatchesBadge'           => 0,
                'newUsersCount'            => 0,
            ];

            if (Auth::check()) {
                $user    = Auth::user();
                $userId  = $user->id;
                $isAdmin = $user->isAdmin();

                // Unread notifications
                $vars['unreadNotificationsCount'] = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();

                // Unread messages
                $vars['totalUnread'] = $user->unreadMessagesCount();

                // Lost Items badge
                $vars['sidebarLostBadge'] = $isAdmin
                    ? LostItem::where('status', 'pending')->count()
                    : LostItem::where('user_id', $userId)->where('status', 'pending')->count();

                // Found Items badge
                $vars['sidebarFoundBadge'] = $isAdmin
                    ? FoundItem::where('status', 'pending')->count()
                    : FoundItem::where('user_id', $userId)->where('status', 'pending')->count();

                // Matches badge — pending matches involving this user (all for admin)
                $matchQuery = ItemMatch::where('status', 'pending');
                if (!$isAdmin) {
                    $matchQuery->where(function ($q) use ($userId) {
                        $q->whereHas('lostItem', fn($q) => $q->where('user_id', $userId))
                          ->orWhereHas('foundItem', fn($q) => $q->where('user_id', $userId));
                    });
                }
                $vars['pendingMatchesCount'] = $matchQuery->count();

                // MY ITEMS section badges (non-admin only, same values)
                if (!$isAdmin) {
                    $vars['myLostBadge']    = $vars['sidebarLostBadge'];
                    $vars['myFoundBadge']   = $vars['sidebarFoundBadge'];
                    $vars['myMatchesBadge'] = $vars['pendingMatchesCount'];
                }

                // New users badge (admin only) — users registered after admin last viewed the users page
                if ($isAdmin) {
                    $cacheKey  = "admin_{$userId}_users_last_seen";
                    $lastSeen  = Cache::get($cacheKey, now()->subDays(7));
                    $vars['newUsersCount'] = User::where('created_at', '>', $lastSeen)
                        ->where('id', '!=', $userId)
                        ->count();
                }
            }

            $view->with($vars);
        });
    }
}
