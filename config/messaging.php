<?php

return [
    'attachments' => [
        // Adjust limits cautiously: raising these increases disk usage and request payload sizes.
        // Ensure php.ini upload_max_filesize and post_max_size support any increases.
        'max_per_message' => 5,
        'max_size_kb' => 5120, // 5MB logical cap; tune if most users need larger files.
        'allowed_mimes' => [
            'image/jpeg','image/png','image/gif','image/webp',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ],
    ],
];
