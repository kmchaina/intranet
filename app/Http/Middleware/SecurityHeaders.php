<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Build CSP directives
        $isLocal = app()->environment('local');

        // In local development, use Report-Only mode to see violations without blocking
        // In production, use enforcing mode
        if ($isLocal) {
            // Report-Only mode for development - logs violations but doesn't block
            $response->headers->set(
                'Content-Security-Policy-Report-Only',
                "default-src 'self'; " .
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 https://cdn.jsdelivr.net https://js.pusher.com; " .
                    "style-src 'self' 'unsafe-inline' http://localhost:5173 https://fonts.googleapis.com; " .
                    "img-src 'self' data: https: blob:; " .
                    "font-src 'self' data: https://fonts.gstatic.com; " .
                    "connect-src 'self' http://localhost:5173 ws://localhost:5173 wss: https:; " .
                    "frame-ancestors 'self'; " .
                    "base-uri 'self'; " .
                    "form-action 'self';"
            );
        } else {
            // Enforcing mode for production
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; " .
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://js.pusher.com; " .
                    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                    "img-src 'self' data: https: blob:; " .
                    "font-src 'self' data: https://fonts.gstatic.com; " .
                    "connect-src 'self' wss: https:; " .
                    "frame-ancestors 'self'; " .
                    "base-uri 'self'; " .
                    "form-action 'self';"
            );
        }

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Enable XSS protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy (formerly Feature Policy)
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), ' .
                'microphone=(), ' .
                'camera=(), ' .
                'payment=(), ' .
                'usb=(), ' .
                'magnetometer=(), ' .
                'gyroscope=(), ' .
                'accelerometer=()'
        );

        // Strict Transport Security (HTTPS only - uncomment in production with HTTPS)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
