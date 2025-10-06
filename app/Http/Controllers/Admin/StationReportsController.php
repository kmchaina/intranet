<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Event;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StationReportsController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser instanceof User) {
            abort(401);
        }

        if (!$currentUser->isStationAdmin() && !$currentUser->isCentreAdmin() && !$currentUser->isHqAdmin() && !$currentUser->isSuperAdmin()) {
            abort(403);
        }

        $requestedStationId = $currentUser->isStationAdmin()
            ? $currentUser->station_id
            : $request->integer('station_id');

        $station = $this->resolveStation($requestedStationId, $currentUser);

        if (!$station) {
            abort(404, 'No station data available.');
        }

        if ($currentUser->isStationAdmin() && $station->id !== $currentUser->station_id) {
            abort(403);
        }

        $metrics = $this->buildMetrics($station, $currentUser);
        $topContributors = $this->buildTopContributors($station);
        $upcomingEvents = $this->buildUpcomingEvents($station);
        $recentAnnouncements = $this->buildRecentAnnouncements($station);

        $station->loadMissing('centre:id,name');

        $availableStations = $currentUser->isStationAdmin()
            ? collect()
            : Station::when($currentUser->isCentreAdmin(), function ($query) use ($currentUser) {
                $query->where('centre_id', $currentUser->centre_id);
            })->orderBy('name')->get(['id', 'name', 'centre_id']);

        return view('admin.station.reports.index', [
            'station' => $station,
            'metrics' => $metrics,
            'topContributors' => $topContributors,
            'upcomingEvents' => $upcomingEvents,
            'recentAnnouncements' => $recentAnnouncements,
            'availableStations' => $availableStations,
            'selectedStationId' => $station->id,
        ]);
    }

    protected function resolveStation(?int $stationId, $currentUser): ?Station
    {
        $query = Station::with(['users:id,station_id,centre_id']);

        if ($currentUser->isStationAdmin()) {
            $query->where('id', $currentUser->station_id);
        } elseif ($stationId) {
            $query->where('id', $stationId);
        } elseif ($currentUser->isCentreAdmin()) {
            $query->where('centre_id', $currentUser->centre_id);
        }

        $station = $query->first();

        if (!$station && !$currentUser->isStationAdmin()) {
            $fallbackQuery = Station::with('users:id,station_id,centre_id');

            if ($currentUser->isCentreAdmin()) {
                $fallbackQuery->where('centre_id', $currentUser->centre_id);
            }

            $station = $fallbackQuery->orderBy('name')->first();
        }

        return $station;
    }

    protected function buildMetrics(Station $station, $currentUser): array
    {
        $userIds = $station->users()->pluck('id');

        return [
            'staff_total' => $userIds->count(),
            'recent_logins' => User::whereIn('id', $userIds)
                ->whereNotNull('email_verified_at')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count(),
            'announcements_created' => Announcement::whereHas('creator', function ($query) use ($station) {
                $query->where('station_id', $station->id);
            })->where('created_at', '>=', now()->subDays(30))->count(),
            'documents_uploaded' => Document::whereHas('uploader', function ($query) use ($station) {
                $query->where('station_id', $station->id);
            })->where('created_at', '>=', now()->subDays(30))->count(),
            'events_scheduled' => Event::whereHas('creator', function ($query) use ($station) {
                $query->where('station_id', $station->id);
            })->where('start_datetime', '>=', now())->count(),
        ];
    }

    protected function buildTopContributors(Station $station)
    {
        return User::where('station_id', $station->id)
            ->withCount([
                'announcements as content_count' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(60));
                },
            ])
            ->orderByDesc('content_count')
            ->take(5)
            ->get();
    }

    protected function buildUpcomingEvents(Station $station)
    {
        return Event::whereHas('creator', function ($query) use ($station) {
            $query->where('station_id', $station->id);
        })
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->take(3)
            ->get();
    }

    protected function buildRecentAnnouncements(Station $station)
    {
        return Announcement::whereHas('creator', function ($query) use ($station) {
            $query->where('station_id', $station->id);
        })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}
