<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        //
    }
}