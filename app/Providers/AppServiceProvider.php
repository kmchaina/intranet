<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ConversationAccessCache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ConversationAccessCache::class, fn() => new ConversationAccessCache());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // No provider macros — use explicit query()->scopeName() calls in code to avoid cross-file issues.
    }
}
