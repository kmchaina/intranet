<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        Log::channel('security')->info('User login successful', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'name' => $event->user->name,
            'role' => $event->user->role,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'guard' => $event->guard,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Also log to activity events for analytics
        \App\Services\ActivityLogger::log(
            'user_login',
            'User',
            $event->user->id,
            [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );
    }
}
