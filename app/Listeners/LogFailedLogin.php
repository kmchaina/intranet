<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'unknown';
        $ip = request()->ip();

        Log::channel('security')->warning('Failed login attempt', [
            'email' => $email,
            'ip' => $ip,
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Track failed attempts by IP for rate limiting
        $key = 'login-attempts:' . $ip;
        RateLimiter::hit($key, 3600); // Track for 1 hour

        // Check if this IP has exceeded threshold
        $attempts = RateLimiter::attempts($key);

        if ($attempts > 5) {
            Log::channel('security')->alert('Possible brute force attack detected', [
                'ip' => $ip,
                'email' => $email,
                'attempts' => $attempts,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // In production, you might want to:
            // 1. Send alert to admins
            // 2. Temporarily block this IP
            // 3. Trigger additional security measures
        }

        // Track by email as well (if provided)
        if ($email !== 'unknown') {
            $emailKey = 'login-attempts:email:' . $email;
            RateLimiter::hit($emailKey, 3600);

            $emailAttempts = RateLimiter::attempts($emailKey);
            if ($emailAttempts > 5) {
                Log::channel('security')->warning('Multiple failed attempts for email', [
                    'email' => $email,
                    'attempts' => $emailAttempts,
                    'ip' => $ip,
                ]);
            }
        }
    }
}
