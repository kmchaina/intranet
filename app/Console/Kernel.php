<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Messaging cleanup scheduled below (added in Phase 4 cleanup enhancement)
        if (config('messaging.temp_tokens.enable_cleanup', true)) {
            $schedule->command('messaging:purge-temp-attachments')->hourly()->environments(['production', 'staging', 'local']);
        }
        // Phase 9: Orphan persistent message attachments cleanup (default daily)
        $schedule->command('messaging:purge-orphan-attachments --older=6')->daily()->environments(['production', 'staging', 'local']);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
