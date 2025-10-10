<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Logout;
use App\Services\ConversationAccessCache;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogLogout;

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

        // Register authentication event listeners for security logging
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Failed::class, LogFailedLogin::class);
        Event::listen(Logout::class, LogLogout::class);
    }
}
