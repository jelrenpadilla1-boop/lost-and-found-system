<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Policies\UserPolicy;
use App\Policies\LostItemPolicy;
use App\Policies\FoundItemPolicy;
use App\Policies\ItemMatchPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        LostItem::class => LostItemPolicy::class,
        FoundItem::class => FoundItemPolicy::class,
        ItemMatch::class => ItemMatchPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates here if needed
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
        
        // Super admin gate
        Gate::define('super-admin', function ($user) {
            return $user->isAdmin() && $user->role === 'super_admin';
        });
        
        // Moderator gate
        Gate::define('moderator', function ($user) {
            return $user->isAdmin() || $user->role === 'moderator';
        });
    }
}