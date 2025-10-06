<?php

namespace App\Providers;

use App\Models\Poll;
use App\Policies\PollPolicy;
use App\Models\Conversation;
use App\Policies\ConversationPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Poll::class => PollPolicy::class,
        Conversation::class => ConversationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isHqAdmin', fn(User $user) => $user->isHqAdmin());
        Gate::define('isCentreAdmin', fn(User $user) => $user->isCentreAdmin());
        Gate::define('isStationAdmin', fn(User $user) => $user->isStationAdmin());
    }
}
