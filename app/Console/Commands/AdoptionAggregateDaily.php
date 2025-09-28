<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\ActivityEvent;
use App\Models\AdoptionDaily;
use App\Models\User;
use Carbon\Carbon;

class AdoptionAggregateDaily extends Command
{
    protected $signature = 'adoption:aggregate-daily {--date= : YYYY-MM-DD date to aggregate (default yesterday)}';
    protected $description = 'Aggregate raw activity_events into adoption_daily metrics';

    public function handle(): int
    {
        if (!Config::get('adoption.enabled', true)) {
            $this->info('Adoption feature disabled. Skipping.');
            return self::SUCCESS;
        }

        $date = $this->option('date') ? Carbon::parse($this->option('date')) : now()->subDay();
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $this->info("Aggregating metrics for {$date->toDateString()}");

        $dayQuery = ActivityEvent::query()->whereBetween('created_at', [$start, $end]);

        $dau = (clone $dayQuery)->distinct('user_id')->count('user_id');
        $announcementReads = (clone $dayQuery)->where('event_type','announcement.read')->count();
        $documentViews = (clone $dayQuery)->where('event_type','document.view')->count();
        $documentDownloads = (clone $dayQuery)->where('event_type','document.download')->count();
        $pollViews = (clone $dayQuery)->where('event_type','poll.view')->count();
        $pollResponses = (clone $dayQuery)->where('event_type','poll.respond')->count();
        $vaultAccesses = (clone $dayQuery)->where('event_type','vault.access')->count();
        $vaultViews = (clone $dayQuery)->where('event_type','vault.view')->count();

        // WAU = distinct users in trailing 7 days including this day
        $wauWindowStart = $date->copy()->subDays(6)->startOfDay();
        $wau = ActivityEvent::query()->whereBetween('created_at', [$wauWindowStart, $end])
            ->distinct('user_id')->count('user_id');

        // Previous week WAU (days -13 to -7 inclusive)
        $prevWeekStart = $date->copy()->subDays(13)->startOfDay();
        $prevWeekEnd = $date->copy()->subDays(7)->endOfDay();
        $wauPrevWeek = ActivityEvent::query()->whereBetween('created_at', [$prevWeekStart, $prevWeekEnd])
            ->distinct('user_id')->count('user_id');

        // MAU = distinct users trailing 30 days
        $mauWindowStart = $date->copy()->subDays(29)->startOfDay();
        $mau = ActivityEvent::query()->whereBetween('created_at', [$mauWindowStart, $end])
            ->distinct('user_id')->count('user_id');

        // Eligible users = all non-null email users (you can refine: exclude disabled column if exists)
        $eligibleUsers = User::query()->count();

        // Coverage = WAU / eligible
        $coveragePct = $eligibleUsers > 0 ? round(($wau / $eligibleUsers) * 100, 2) : 0;
        // Stickiness = DAU / MAU
        $stickinessPct = $mau > 0 ? round(($dau / $mau) * 100, 2) : 0;

        // Activation absolute
        $newUserActivation = $this->calculateActivation($date);
        // Activation rate = activated / new users in window
        $newUsersWindow = $this->countNewUsersWindow($date);
        $activationRatePct = $newUsersWindow > 0 ? round(($newUserActivation / $newUsersWindow) * 100, 2) : 0;

        // Top feature concentration (largest share of total interaction events for the day)
        $featureEvents = [
            'announcement.read' => $announcementReads,
            'document.view' => $documentViews,
            'poll.respond' => $pollResponses,
            'vault.access' => $vaultAccesses,
            'document.download' => $documentDownloads,
            'poll.view' => $pollViews,
            'vault.view' => $vaultViews,
        ];
        $totalFeature = array_sum($featureEvents);
        $topFeaturePct = $totalFeature > 0 ? round((max($featureEvents) / $totalFeature) * 100, 2) : 0;

        AdoptionDaily::updateOrCreate(
            ['date' => $date->toDateString()],
            [
                'dau' => $dau,
                'wau' => $wau,
                'announcement_reads' => $announcementReads,
                'document_views' => $documentViews,
                'document_downloads' => $documentDownloads,
                'poll_views' => $pollViews,
                'poll_responses' => $pollResponses,
                'vault_accesses' => $vaultAccesses,
                'vault_views' => $vaultViews,
                'new_user_activation' => $newUserActivation,
                'eligible_users' => $eligibleUsers,
                'mau' => $mau,
                'wau_prev_week' => $wauPrevWeek,
                'coverage_pct' => $coveragePct,
                'stickiness_pct' => $stickinessPct,
                'activation_rate_pct' => $activationRatePct,
                'top_feature_pct' => $topFeaturePct,
            ]
        );

        $this->info('Aggregation complete.');
        return self::SUCCESS;
    }

    private function calculateActivation(Carbon $date): int
    {
        $windowStart = $date->copy()->subDays(6)->startOfDay();
        $windowEnd = $date->copy()->endOfDay();

        $firstEvents = ActivityEvent::query()
            ->select('user_id', DB::raw('MIN(created_at) as first_at'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingBetween('first_at', [$windowStart, $windowEnd])
            ->get();

        $activated = 0;
        foreach ($firstEvents as $row) {
            $activationEnd = Carbon::parse($row->first_at)->addDays(6)->endOfDay();
            $count = ActivityEvent::query()
                ->where('user_id', $row->user_id)
                ->whereBetween('created_at', [Carbon::parse($row->first_at), $activationEnd])
                ->count();
            if ($count >= 3) {
                $activated++;
            }
        }
        return $activated;
    }

    private function countNewUsersWindow(Carbon $date): int
    {
        $windowStart = $date->copy()->subDays(6)->startOfDay();
        $windowEnd = $date->copy()->endOfDay();

        return ActivityEvent::query()
            ->select('user_id', DB::raw('MIN(created_at) as first_at'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingBetween('first_at', [$windowStart, $windowEnd])
            ->get()
            ->count();
    }
}
