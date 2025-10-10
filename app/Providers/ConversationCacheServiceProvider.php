<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ConversationAccessCache;

class ConversationCacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ConversationAccessCache::class, function () {
            return new ConversationAccessCache();
        });
    }

    public function boot(): void
    {
        // Nothing additional
    }
}
