<?php

namespace App\Services;

use App\Models\ActivityEvent;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public static function log(string $eventType, string $entityType = null, int $entityId = null, array $metadata = []): void
    {
        if (!Config::get('adoption.enabled', true)) {
            return;
        }

        try {
            $event = ActivityEvent::create([
                'user_id' => Auth::id(),
                'event_type' => $eventType,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'metadata' => $metadata ?: null,
            ]);
            // Update badge progress
            BadgeService::record($eventType, $event->user_id);
        } catch (\Throwable $e) {
            Log::warning('ActivityLogger failure: ' . $e->getMessage());
        }
    }
}
