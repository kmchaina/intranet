<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if ($event->user) {
            Log::channel('security')->info('User logout', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'name' => $event->user->name,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'guard' => $event->guard,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Log to activity events
            \App\Services\ActivityLogger::log(
                'user_logout',
                'User',
                $event->user->id,
                [
                    'ip' => request()->ip(),
                ]
            );
        }
    }
}
