<?php

return [
    'attachments' => [
        // Adjust limits cautiously: raising these increases disk usage and request payload sizes.
        // Ensure php.ini upload_max_filesize and post_max_size support any increases.
        'max_per_message' => 5,
        'max_size_kb' => 5120, // 5MB logical cap; tune if most users need larger files.
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ],
    ],
    'rate_limit' => [
        // Messages a single authenticated user may send per minute.
        // Can be overridden in .env via MESSAGES_RATE_PER_MINUTE.
        'messages_per_minute' => env('MESSAGES_RATE_PER_MINUTE', 30),
    ],
    'temp_tokens' => [
        // How long (minutes) unused attachment tokens remain before purge.
        'ttl_minutes' => env('MESSAGING_TEMP_TOKEN_TTL', 60),
        // Master switch for scheduled purge.
        'enable_cleanup' => env('MESSAGING_TEMP_TOKEN_CLEANUP', true),
    ],
    'typing' => [
        // Minimum seconds between consecutive "is typing" broadcasts for the same user & conversation.
        'throttle_seconds' => env('MESSAGING_TYPING_THROTTLE', 2),
        // Clients should clear the indicator if no update within this idle timeout.
        'idle_timeout_seconds' => env('MESSAGING_TYPING_IDLE_TIMEOUT', 6),
    ],
    'search' => [
        // Maximum number of results to return per query
        'max_results' => env('MESSAGING_SEARCH_MAX_RESULTS', 20),
        // Minimum query string length
        'min_length' => env('MESSAGING_SEARCH_MIN_LENGTH', 2),
    ],
    'presence' => [
        // Heartbeat TTL seconds a user is considered online after last ping
        'ttl_seconds' => env('MESSAGING_PRESENCE_TTL', 60),
        // Broadcast channel name for presence diff events
        'channel' => 'messaging.presence',
    ],
    'drafts' => [
        // Maximum draft body length (mirrors message limit)
        'max_length' => 5000,
    ],
];
